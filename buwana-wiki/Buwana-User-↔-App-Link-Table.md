## 🔗 `user_app_connections_tb`

The `user_app_connections_tb` is a simple table that acts as a **many-to-many relationship** bridge between `users_tb` and `apps_tb`. It allows Buwana to track which users have connected, registered, or authenticated with which apps in the ecosystem (e.g., GoBrik, EarthCal, OpenBooks, etc.).

This separation ensures that users can access and manage multiple apps using a single Buwana ID while keeping each app's logic and permissions modular.

---

### 🧱 Table Structure

| Field              | Type         | Description |
|--------------------|--------------|-------------|
| `connection_id`    | `int(11)`    | Primary key (auto-incremented). Unique ID for each connection. |
| `buwana_id`        | `int(11)`    | FK to `users_tb`. Refers to the user. |
| `app_id`           | `int(11)`    | FK to `apps_tb`. Refers to the app they are linked with. |
| `connected_at`     | `datetime`   | When the connection was created (e.g., when user registered or logged in to the app). |
| `last_used_at`     | `datetime`   | The last time this user logged in to this app. |
| `connection_status`| `varchar(50)`| Status of the relationship. E.g., `active`, `suspended`, `pending_verification`. |
| `notes`            | `text`       | Optional notes about the connection (e.g., flags, user behavior, debug info). |

---

### 🔐 Foreign Keys

- `buwana_id` → `users_tb.buwana_id`
- `app_id` → `apps_tb.app_id`

---

### 🧠 Use Cases

- Identify all apps a specific user has registered with.
- Show “Connected Apps” in user dashboards.
- Track cross-app usage patterns or preferences.
- Enable future granular permission systems (e.g., revoke app access).

---

### 📝 Example Entry

| connection_id | buwana_id | app_id | connected_at       | last_used_at        | connection_status | notes               |
|---------------|-----------|--------|--------------------|---------------------|-------------------|----------------------|
| 1             | 2483      | 7      | 2024-04-14 10:22:00| 2025-04-17 09:00:00 | active            | "Connected via GoBrik signup" |

---

### 🚦Status: `Active`

This table is in use and required for all Buwana-based applications. It supports scalable app linkage for a shared authentication infrastructure across the regenerative app ecosystem.

---

### 📘 Related Tables

- [`users_tb`](Buwana-Users-Table.md)
- [`apps_tb`](apps_tb.md)
