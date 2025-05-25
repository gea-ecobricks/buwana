# 🧭 Buwana Signup Flow Breakdown
*A step-by-step guide from `signup-1` to `signup-7`, including purpose, frontend input, backend actions, and client integration.*

---

### 🧩 Step 1: `signup-1.php` — Name & Credential Type

**Purpose:**  
Capture the user's name and preferred login method (email or phone).

**User Input:**
- `first_name`
- `credential_type`

**Backend:**
- JavaScript validation (live + on submit)
- No backend PHP processing at this stage

**Next:**  
Form is submitted to `signup-2.php`

---

### 🧩 Step 2: `signup-2.php` — Enter Credential & Anti-Bot Check

**Purpose:**  
Collect login credential and screen for bots.

**User Input:**
- `credential_key` (email or phone)

**Backend (`signup-2_process.php`):**
- Honeypot field detection
- Time-based bot check
- Credential validation and uniqueness check
- Stores session data
- Redirects to `signup-3.php`

---

### 🧩 Step 3: `signup-3.php` — Credential Verification

**Purpose:**  
Verify ownership of email or phone number.

**User Input:**
- Verification code

**Backend (`signup-3_process.php`):**
- Validates submitted code
- Expiry & rate limits enforced
- Marks user as verified
- Redirects to `signup-4.php`

---

### 🧩 Step 4: `signup-4.php` — Bioregional Localization

**Purpose:**  
Collect watershed and location data for bioregional mapping.

**User Input:**
- `river_name`
- `location_full` (optional)

**Backend (`signup-4_process.php`):**
- River is matched to watershed ID
- Geolocation fields populated
- Updates user’s watershed & location
- Redirects to `signup-5.php`

---

### 🧩 Step 5: `signup-5.php` — Newsletter Subscriptions

**Purpose:**  
Let user subscribe to Earthen newsletters.

**User Input:**
- `subscriptions[]`

**Backend (`signup-5_process.php`):**
- Adds user to Ghost CMS (or updates membership)
- Updates `users_tb`:
  - `account_status = 'registered and subscribed, no login'`
  - `terms_of_service = 1`
- Redirects to `signup-6.php?id=XYZ`

---

### 🧩 Step 6: `signup-6.php` — Finalize Account + Client Sync

**Purpose:**  
Gather final profile info and create the user in the client app.

**User Input:**
- `country_id`
- `language_id`
- `community_name`
- `earthling_emoji`

**Backend (`signup-6_process.php`):**
- Updates `users_tb` with selected fields
- Loads client app config (e.g., `gobrik_env.php`)
- Calls `create_user.php`:
  - Inserts user into client app DB (`users_tb` or `tb_ecobrickers`)
  - Updates `user_app_connections_tb` with status = `registered`
- Redirects to `signup-7.php?id=XYZ`

---

### 🧩 Step 7: `signup-7.php` — Confirmation & Redirect

**Purpose:**  
Thank the user and redirect to app dashboard.

**Backend:**
- Fetches `first_name`, `earthling_emoji` from DB
- Loads app info from `fetch_app_info.php`

**Frontend:**
- Displays final success message
- Countdown redirect to client app login URL
- Translation support + RTL layout

---

### 🔄 Supporting Systems

| System       | Purpose                              |
|--------------|--------------------------------------|
| Ghost CMS    | Manages newsletter subscriptions     |
| Client Apps  | User record provisioning             |
| Buwana DB    | Identity and centralized user fields |

---

### 🔐 Privacy & Terms Handling

- Translation text in `buwana-terms-xx.js`
- Dynamic client app name injected via `{{appName}}`
- Shown in modal via `openBuwanaPrivacy()`
