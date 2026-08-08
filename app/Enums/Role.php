<?php

namespace App\Enums;

enum Role: string
{
    use EnumToArray;

    case Admin = "admin";
    case User = "user";
    case Manager = "manager";
}
