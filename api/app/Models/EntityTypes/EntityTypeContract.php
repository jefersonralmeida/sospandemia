<?php

namespace App\Models\EntityTypes;

use Illuminate\Http\Request;

interface EntityTypeContract
{
    public function search(array $query);
    public function validate(Request $request, ?string &$error = '');
}
