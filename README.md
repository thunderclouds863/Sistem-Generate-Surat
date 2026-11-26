# Sistem Generate Surat — HMTI UNDIP

Web-based administrative letter generation and 2-tier approval system for **Himpunan Mahasiswa Teknik Industri (HMTI), Universitas Diponegoro**.

## Features
- **Role-Based Access Control**:
  - **Anggota**: Register/login, create 3 letter types (*Surat Undangan*, *Surat Permohonan*, *Surat Peminjaman*) via 2-step forms, track real-time 2-tier approval status, and download approved `.docx` documents.
  - **Sekretaris Himpunan (`sekhim`)**: Review submitted letters, inspect `.docx` drafts, and approve (forwarding to Kahim) or reject submissions.
  - **Ketua Himpunan (`kahim`)**: Review Sekhim-verified letters and grant final approval (unlocking member `.docx` download) or reject submissions.
- **Automated `.docx` Generation**: Populates official HMTI Word templates (`surat-undangan.docx`, `surat-permohonan.docx`, `surat-peminjaman.docx`) via Python `docxtpl`.
- **Security & Data Integrity**: Centralized DB & session bootstrap (`includes/bootstrap.php`), transactional letter creation with duplicate `nomor_surat` checks, CSRF tokens on POST actions, RBAC route guards, and safe `.docx` streaming.

## Quick Start (Docker — Recommended)
The included `Dockerfile` and `docker-compose.yml` bundle Apache, PHP 8.2, MariaDB, and Python (`docxtpl` + `mysql-connector-python`) and automatically import `hmti_system.sql` and `deploy/demo_seed.sql`.

```bash
docker compose up --build
```

Then open **http://localhost:10000**.

### Demo Accounts
| Role | Login Page | Username | Password |
| :--- | :--- | :--- | :--- |
| Anggota | `index.html` | `anggota1` | `demo123` |
| Sekretaris Himpunan | `login_role.php` | `sekhim` | `sekhim123` |
| Ketua Himpunan | `login_role.php` | `kahim` | `kahim123` |

## Local Setup (XAMPP / Laragon / Native PHP + MariaDB)
1. Start Apache (with `.html` parsed as PHP or access via `.php` routes) and MariaDB/MySQL.
2. Import the database schema and optional seed data:
   ```bash
   mysql -u root < hmti_system.sql
   mysql -u root < deploy/demo_seed.sql
   ```
3. Install Python dependencies for `.docx` generation:
   ```bash
   pip install docxtpl mysql-connector-python
   ```
4. Optional environment variables (defaults match local XAMPP/MariaDB):
   - `DB_HOST` (default: `localhost`)
   - `DB_USER` (default: `root`)
   - `DB_PASS` (default: empty)
   - `DB_NAME` (default: `HMTI_System`)
   - `DB_PORT` (default: `3306`)
