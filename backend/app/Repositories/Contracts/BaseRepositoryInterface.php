<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface extends ReadRepositoryInterface, WriteRepositoryInterface
{
    // Geriye uyumluluk için ReadRepositoryInterface ve WriteRepositoryInterface'i extend eder
}