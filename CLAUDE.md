# Project Overview

This repository is a Laravel 12 backend project used to learn and experiment with Claude Code in a real codebase.

Primary goals:
- practice using Claude Code with a Laravel project
- keep code changes safe and minimal
- follow consistent project conventions
- support common workflows such as feature analysis, implementation, review, refactoring, and testing

This project focuses on backend Laravel development.
Frontend tooling is out of scope unless explicitly requested.

# Tech Stack

- Laravel 12
- PHP 8.3
- PHP-FPM
- Nginx
- MySQL 8.0
- Docker
- Docker Compose
- PHPUnit

# Environment

The application runs inside Docker containers.

Main services:
- app: PHP-FPM application container
- nginx: web server
- mysql: database

Application URL:
- http://localhost:8080

Important notes:
- Prefer Docker-based commands over local machine commands
- Assume the main runtime is the `app` container
- Database host inside Docker is `mysql`

Useful commands:
- make build
- make up
- make down
- make composer-install
- make key
- make migrate
- make test
- docker compose exec app php artisan optimize:clear

# Working Style

When working on a task:
1. Explore the relevant files first
2. Briefly explain the current flow when the task is non-trivial
3. Propose a minimal plan before making broad changes
4. Implement the smallest safe change
5. Verify the result with appropriate commands when possible

Prefer consistency over creativity.
Do not make broad refactors unless explicitly requested.

# Project Architecture

Follow the existing Laravel project structure in this repository.

Main conventions:
- Controllers should stay thin
- Business logic should live in `app/Services`
- Data access logic should live in `app/Repositories` when repository pattern is already being used
- Data transfer objects should live in `app/Data`
- Custom validation rules should live in `app/Rules`
- Enums should live in `app/Enums`
- Helper functions should live in `app/Helpers` only when they clearly match existing patterns

Use existing patterns before introducing new ones.

# Laravel Conventions

- Use Form Request validation when validation becomes non-trivial
- Prefer Eloquent relationships and scopes over raw queries
- Avoid duplicated logic across controllers and services
- Keep methods small, readable, and focused
- Use dependency injection when appropriate
- Follow PSR-12
- Use clear and descriptive names
- Avoid unnecessary abstractions

# File Change Rules

- Prefer minimal changes
- Do not modify unrelated files
- Do not rename classes, files, or folders without a clear reason
- Do not add new packages unless explicitly requested
- Do not change database schema unless the task requires it
- Do not replace an existing pattern with a new architecture unless explicitly requested

# Testing

When behavior changes, add or update tests when appropriate.

Testing guidance:
- Prefer focused tests
- Follow the existing project test style
- Cover the main success path
- Cover the main failure path when relevant

Run tests with:
- make test
- docker compose exec app php artisan test

# Git Workflow

Branch structure:
- `master` — production
- `staging` — pre-production
- `develop` — integration

Branch naming:
- `feature/main` — checkout from `develop`, for major features
- `feature/medium` — checkout from `feature/main`, for sub-tasks

Merge flow:
- Deploy/release a feature by merging it into `develop` → `staging` → `master`
- Do not merge unrelated branches together

Commit messages: use short, descriptive lowercase imperative (e.g. `add user login api`)

# API Response Format

All API responses follow a consistent JSON structure.

## 200 Success with data

```json
{
  "success": true,
  "data": { ... }
}
```

## 422 Validation Failed

```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "field": ["Validation message."]
  }
}
```

## 401 Unauthenticated

```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

## 403 Forbidden

```json
{
  "success": false,
  "message": "This action is unauthorized."
}
```

## 500 Server Error

```json
{
  "success": false,
  "message": "Server error."
}
```

Do not return raw Laravel exception responses. Always wrap in the above format.

# Output Expectations

When making code changes, provide:
- a short summary of what changed
- the files affected
- any verification commands that were run
- any follow-up risks or notes if relevant
