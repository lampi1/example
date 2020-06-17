<?php

namespace App\Daran\MetaTags;

interface HasMeta
{

    public function getMetaTitle(): string;

    public function getOgTitle(): string;

    public function getMetaDescription(): string;

    public function getOgDescription(): string;

    public function getOgImage(): string;

    public function getTranslations(): ?array;

    public function isSeoIndexable(): bool;
}
