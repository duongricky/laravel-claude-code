# Laravel Claude Code Lab

Dockerized **Laravel 12** environment using:

- Nginx
- PHP-FPM 8.3
- MySQL 8.0

This repository is used to experiment with **Claude Code workflows** in a real Laravel project, focusing on:

- AI-assisted refactoring
- code review agents
- test generation
- debugging workflows
- structured prompt experimentation

Frontend tooling (Node/Vite) is intentionally not used. The focus is backend Laravel development.

---

# Requirements

- Docker
- Docker Compose
- Make

---

# Build Local Environment

Run the following commands:

```bash
make build
make composer-install
make key
make migrate
make up
