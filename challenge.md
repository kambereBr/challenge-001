# Technical Challenge – Pure PHP 7.2 + HTML (Stores & Weapons CRUD)

> **Important Stack Constraint**
> This challenge **must be implemented in *plain PHP 7.2* (no frameworks)** plus vanilla **HTML/CSS/JS**. Do **not** use Laravel, Symfony, CodeIgniter, or any other full‑stack / MVC framework. Lightweight utility libraries (e.g., for PDF generation such as FPDF / TCPDF / mPDF) are allowed, as are small helper classes you write yourself. If you include third‑party libraries, document how to install them (Composer or vendor drop‑in) and keep dependencies minimal.

---

## Goal

Assess your ability to design and implement a small but complete **pure PHP** web application that:

* Models simple relational data (**Stores** ↔ **Weapons**).
* Exposes minimal REST‑like endpoints (or classic PHP page controllers that return HTML & JSON as needed).
* Renders interactive HTML tables with **sorting, filtering, and pagination**.
* Supports **entity‑to‑entity navigation** (Weapon → Store; Store → Weapons).
* Generates a **PDF export** for a Weapon record.

## Business Context (Scenario)

You are building an internal admin tool for a (fictional) sporting goods group that manages multiple **Stores** and their inventory of **Weapons** (firearms / sporting arms). Staff need to add, edit, browse, and report on stores and weapons. They also need to print a one‑page PDF “spec sheet” for any weapon, including the store that carries it.

---

## What You Must Build

A small web application (PHP 7.2 + HTML) that provides:

* Full **CRUD** (create, read, update, delete) for **Stores**.
* Full **CRUD** for **Weapons**.
* **Relational link:** each Weapon belongs to exactly one Store.
* From the **Weapons table/list**, users can click to navigate to the linked Store detail page.
* From the **Store detail page**, users can view a list/table of all Weapons registered to that Store.
* In the **Weapons table/list**, each row must have an action to **print/export that Weapon to PDF**.
* All list/table views must support **sorting, filtering, and pagination** across every displayed column (where meaningful).
* Works in a standard LAMP‑style environment (Apache or Nginx + PHP‑FPM) without special build tooling.

---

## Functional Requirements

### 1. Entities

Model two core entities. Provide SQL DDL in `/db/schema.sql` (and optionally migrations scripts) to create these tables.

**stores** (suggested columns – extend if useful):

* `id` (INT auto‑increment **primary key** or UUID char(36))
* `name` VARCHAR(..) required unique
* `slug` VARCHAR(..) URL‑friendly unique identifier
* `address_line1` VARCHAR(..)
* `address_line2` VARCHAR(..) nullable
* `city` VARCHAR(..)
* `state_region` VARCHAR(..)
* `country` VARCHAR(..)
* `phone` VARCHAR(..) nullable
* `email` VARCHAR(..) nullable
* `created_at` DATETIME NOT NULL
* `updated_at` DATETIME NOT NULL

**weapons** (suggested columns – extend if useful):

* `id` INT auto‑increment pk
* `store_id` INT fk → stores.id (ON DELETE CASCADE or RESTRICT; your choice, document it)
* `name` VARCHAR(..) required
* `type` VARCHAR(..) (e.g., rifle, shotgun, handgun)
* `caliber` VARCHAR(..)
* `serial_number` VARCHAR(..) unique
* `price` DECIMAL(10,2)
* `in_stock` INT default 0
* `status` ENUM('active','discontinued','out\_of\_stock') or VARCHAR(..)
* `created_at` DATETIME NOT NULL
* `updated_at` DATETIME NOT NULL

> **Flexibility:** Add columns (image\_url, manufacturer, notes, compliance flags, etc.) if they improve usability. Document any additions in your README and schema file.

### 2. CRUD Operations

Provide HTML forms (create/edit) and actions in PHP to insert/update/delete rows. You may implement soft delete (e.g., `deleted_at` column) instead of hard delete; document which you chose.

### 3. List / Table Views

Implement at least these pages:

* **Stores List** – paginated HTML table of all stores; sortable & filterable by displayed columns; each row links to a Store detail page.
* **Store Detail** – shows Store fields + paginated/filtered table of that Store’s Weapons (sortable & filterable).
* **Weapons List** – global paginated list of all weapons across all stores; includes a Store name column linking to that Store detail; each row has a **Print PDF** action.

### 4. Sorting, Filtering & Pagination

All implemented **server‑side in PHP** (preferred for this challenge so we can see your query building), though light client enhancements are fine.

Implement robust input sanitization and safe SQL (prepared statements required).

### 5. PDF Export (Weapons)

Each row in the Weapons list must offer an action that generates a PDF containing:

* Weapon core fields (name, type, caliber, serial number, price, stock, status).
* Linked Store name + city + contact info (phone/email).
* Simple branded header/footer.

**Implementation guidance:**

* Create a route: `weapon_pdf.php?id=123` (or similar) returning `application/pdf`.
* You may use a PHP PDF lib (FPDF, TCPDF, mPDF, dompdf) **only**; no external service calls.
* PDF must download or open in browser; document expected behaviour.

### 6. Navigation Requirements

* Clicking a Weapon’s Store name (any table) opens the Store detail page.
* From a Store detail, clicking a Weapon opens its edit/detail page.
* Provide consistent URLs (query string or path‑info).

### 7. Validation & Error Handling

* Validate required fields server‑side; re‑render form with user input + error messages.
* Basic client hints (HTML5 `required`, `type="email"`, etc.) welcomed but optional.
* Show graceful error page if DB connection fails.

---

## Technical Requirements (Environment)

**PHP Version:** 7.2.x (use only language features available in 7.2).
**Web Server:** Apache + mod\_php or PHP built‑in dev server (`php -S localhost:8000`) is fine.
**Database:** You may choose **MySQL/MariaDB** or **SQLite**. Provide configuration instructions in README.

**No Frameworks:**

* No Laravel, Symfony, Slim, Lumen, Laminas, CodeIgniter, Cake, etc.
* You may use small helper classes (DB connection wrapper, router, validator) or write one yourself.
* Autoloading: optional simple PSR‑4 via Composer or manual `require` statements; either is fine.


## Data & Sample Seeds

Provide at least:

* **5+ Stores** across different cities/countries.
* **20+ Weapons** spread across those stores.
* Include at least a few out\_of\_stock or discontinued items to exercise filters.

Seed script should be idempotent or include a `reset_db.php` that drops + recreates tables.

---

## UI & UX Guidelines

* Plain HTML + minimal CSS is fine; responsiveness appreciated (simple flex/grid ok).
* Provide a top nav or sidebar to reach Stores list and Weapons list.
* Use HTML tables with `<thead>` for headers and `<tbody>` for rows.
* Show total count + current page indicators.
* Provide links for `Prev` / `Next` (and optionally numbered pages).
* Confirm before destructive actions (simple confirm dialog is fine).

---

## Security Expectations

* Use **prepared statements** (PDO or mysqli) to avoid SQL injection.
* Escape output in HTML contexts (`htmlspecialchars`).
* Basic CSRF protection for POST forms (hidden token + session) is a **bonus**.
* Input validation & sanitization on all request params used in queries.

---

## Testing (Lightweight)

Because we are in pure PHP, full PHPUnit coverage is not required (though welcome). Minimum acceptable testing:

* A simple script or set of curl commands in README that demonstrates each CRUD path.
* Optional: add PHPUnit (compatible w/ PHP 7.2) and include 2–3 basic tests (DB insert/read; filter query; PDF generation returns bytes).

---

## Git Workflow Instructions

1. **Fork** the challenge repository to your GitHub account.
2. **Clone** your fork locally.
3. Create a new **branch** for your work (e.g., `yourname/anynameyoulike`).
4. Commit in logical steps with clear messages (we read history!).
5. Push your branch to your fork.
6. Open a **Pull Request (PR)** back to the original challenge repo.

   * Title: `Challenge Submission – <Your Name>`
   * Description: overview; environment; how to run; known limitations.

---

## Deliverables Checklist

Your PR should include:

* PHP source code (no framework).
* SQL schema.
* Seed script.
* Stores CRUD working.
* Weapons CRUD working.
* Store detail shows Weapons.
* Weapon list links to Store.
* Weapon PDF export working.
* Sorting / Filtering / Pagination implemented.
* README with install & run instructions (PHP 7.2 steps, DB config, sample login if any).
* (Optional) Docker (Apache + PHP + DB) for faster review.

| Item                 | Status (✅/❌) | Notes |
| -------------------- | ------------ | ----- |
| Stores CRUD          |              |       |
| Weapons CRUD         |              |       |
| Store → Weapons list |              |       |
| Weapon → Store link  |              |       |
| Weapon PDF           |              |       |
| Sorting              |              |       |
| Filtering            |              |       |
| Pagination           |              |       |
| Security basics      |              |       |
| Tests / scripts      |              |       |
| Docker (optional)    |              |       |

---

## Evaluation Criteria

We will review and score across these dimensions:

**Functionality** – CRUD completeness; relational integrity; PDF generation.

**Code Quality** – Separation of concerns even without a framework (keep DB logic out of templates); readability; reusability; comments where needed.

**Data Handling** – Correct SQL; safe parameter binding; efficient queries (avoid N+1 when listing Weapons w/ Store names).

**UX & Polish** – Usable tables; clear navigation; inline validation; responsive basics.

**Security Hygiene** – Prepared statements; escaped output; optional CSRF.

**Reproducibility** – Can we set up quickly following README? Do seeds work?

**Communication** – Commit quality; PR description clarity; explanation of trade‑offs.

---

## Bonus Ideas (Optional Extras)

* Docker / docker‑compose one‑command environment (php:7.2‑apache + mysql + seed).
* CSV import/export for Weapons inventory.
* Bulk PDF export (zip of individual PDFs).
* Simple REST JSON endpoints alongside HTML pages (return JSON when `Accept: application/json`).
* Basic search auto‑complete for Stores when creating a Weapon.
---
## Need Help?

If you hit a blocker (setup, unclear requirement, environment issue), open an Issue in your fork and @mention us in the PR, or email your point of contact. We prefer you ask instead of staying stuck.

Good luck — we look forward to reviewing your solution!
