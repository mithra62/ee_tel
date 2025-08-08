<?php

namespace Mithra62\Tel\Extensions;

use ExpressionEngine\Service\Addon\Controllers\Extension\AbstractRoute;

class SetCpCss extends AbstractRoute
{
    public function process()
    {
        return "input[type=tel] {  display: block;
  width: 100%;
  padding: 8px 15px;
  font-size: 1rem;
  line-height: 1.6;
  color: var(--ee-input-color);
  background-color: var(--ee-input-bg);
  background-image: none;
  transition: border-color 200ms ease, box-shadow 200ms ease;
  -webkit-appearance: none;
  border: 1px solid var(--ee-input-border);
  border-radius: 5px;
  box-shadow: 0 1px 2px 0 var(--ee-shadow-input);}";
    }
}
