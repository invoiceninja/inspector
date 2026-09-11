# Changelog

All notable changes to `inspector` will be documented in this file

## 4.0.0 - unreleased

- Dropped support for Laravel versions before 12.
- Added support for Laravel 12 and 13.
- Replaced the removed Doctrine DBAL bridge with Laravel's native schema introspection.
- `getTableSchema()` now returns an array; components accept array `$table`/`$column` values.

## 1.0.0 - 201X-XX-XX

- initial release
