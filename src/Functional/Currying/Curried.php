<?php
/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of chparametere, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional\Currying;

class Curried
{
    private $callback;

    private $arguments;

    public function __construct($arguments)
    {
        if (array_sum(\Functional\invoke($arguments, 'isVariableLength')) > 1) {
            throw new \InvalidArgumentException('curry(): more than one variable argument placeholder is given');
        }

        $this->callback = array_shift($arguments);
        $this->arguments = $arguments;
    }

    public function getCallback()
    {
        if ($this->callback instanceof self) {
            return $this->callback->getCallback();
        }
        return $this->callback;
    }

    public function __invoke()
    {
        $callArgs = func_get_args();
        $boundArgs = $this->arguments;
        $that = $this;

        $isNull = function($v) {return $v === null;};
        $placeholderArgs = \Functional\reject(\Functional\invoke($boundArgs, 'isVariableLength'), $isNull);
        $isTrue = function($v) {return $v;};
        $varArgKey = reset(array_keys(\Functional\select($placeholderArgs, $isTrue)));
        $placeholderArgs = \Functional\reject($placeholderArgs, $isTrue);
        \Functional\map($placeholderArgs, function($v, $k) use(&$callArgs, &$boundArgs, $that) {
            $arg = $boundArgs[$k];
            if (!isset($callArgs[$arg->position - 1])) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Curried %s() requires parameter %d to be passed. None given',
                        $that->getCallback(),
                        $arg->position
                    )
                );
            }
            $boundArgs[$k] = $callArgs[$arg->position - 1];
            unset($callArgs[$arg->position - 1]);
        });

        if ($varArgKey !== false) {
            $left = array_slice($boundArgs, 0, $varArgKey);
            $right = array_slice($boundArgs, $varArgKey + 1);
            $boundArgs = array_merge($left, $callArgs, $right);
        }

        return call_user_func_array($this->callback, $boundArgs);
    }
}

