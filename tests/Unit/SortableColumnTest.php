<?php

namespace Tests\Unit;

use App\Rules\SortableColumn;
use Tests\TestCase;

class SortableColumnTest extends TestCase
{
    /** @test */
    function validates_sortable_values()
    {
        $rule = new SortableColumn(['id', 'first_name', 'email']);

        $this->assertTrue($rule->passes('order', 'id'));
        $this->assertTrue($rule->passes('order', 'id-desc'));
        $this->assertTrue($rule->passes('order', 'first_name'));
        $this->assertTrue($rule->passes('order', 'first_name-desc'));
        $this->assertTrue($rule->passes('order', 'email'));
        $this->assertTrue($rule->passes('order', 'email-desc'));

        $this->assertFalse($rule->passes('order', []));
        $this->assertFalse($rule->passes('order', 'id-descendent'));
        $this->assertFalse($rule->passes('order', 'desc-id'));
        $this->assertFalse($rule->passes('order', 'first_name-descendent'));
        $this->assertFalse($rule->passes('order', 'asc-first_name'));
        $this->assertFalse($rule->passes('order', 'first_name-descx'));
        $this->assertFalse($rule->passes('order', 'email-des'));
        $this->assertFalse($rule->passes('order', 'desc-email'));
    }
}
