<?php

namespace App\Enums;

enum AssignmentType: string
{
    case Homework = 'homework';
    case Project = 'project';
    case Lab = 'lab';
}
