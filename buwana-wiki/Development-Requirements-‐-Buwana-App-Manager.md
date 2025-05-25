## 1. Project Summary

### 1.1 The Basic Concept
The Buwana authentication system addresses the need for a secure, fun and human and Earth-friendly app user authenticaion system that resonates with the core principles of regenerative apps. Buwana provides a resonant alternative to third-party corporate SSO login systems by offering an open, not-for-profit alternative that embodies the core tenets of Earthen ethics.  The Buwana App manager allows admins to setup, deploy and monitor their Buwana integrated apps and user registration statistics.

### 1.2 The BAM Solution
The Buwana App Manager (BAM) will provide a one-stop-shop for admins to setup, deploy and manage their buwana integration and monitor user stats. 

### 1.3 Scope
Buwana accounts serve as core credentials across regenerative applications. BAM users will use their Buwana account to login to the BAM.  There they will have full access to the setup integration of their app (apps_tb) and limited access to view users signed up on BAM and track user registrations and activitiy (users_tb).  BAM thus enables app developers to out-source the deployment a complex app-registration flow and user-management system to the buwana platform and protocol.

- BAM has full access to their app's record inn the apps_tb .
- Buwana users can use the app they signup to... then use that to access other apps in the Buwana ecosystem.
- Independent signup infrastructure.
- Independent login infrastructure.
- Central maintenance of identity, community, bioregion, and earth cycle data in the Buwana app

### 1.4 Benefits for Regenerative App Managers
- Privacy and security aligned with for-Earth principles.
- Remove dependency on corporate authentication platforms.
- Seamless app-to-app credential usage and sharing.
- Benefit of belonging to an ecosystem of regenerative apps
- Make use of users bioregion, community and humanity-score stats in app.


## 2. Process Requirements

### 2.1 General Guidelines
- Users log in via unified Buwana accounts.
- Data stored in `EarthenAuth_db`.
- System designed to support multi-app access.
- BAM is developed as any other Buwana App in the apps_tb
- BAM uses Buwana accounts

### 2.2 Development
- Development of BAM will occur in the Global Ecobrick Alliance's Buwana repository.  This is the same repository that also handles the signup and registration of Buwana users.  https://github.com/gea-ecobricks/Buwana
- The Buwana repository is a fork of the GoBrik repository which is a fork of the Ecobricks-org repos.

### 2.3 BAM Functions
| Function | Description |
|----------|-------------|
| Setup an App | Create the integration for a regenerative app to use Buwana |
| Customize Integraion | Set the language strings, logos, icons, terms of use and more for the app |
| Manage Users | View the users that have signed up on the app, see registration trends |
| View trends | View a graphical display of users signups and registrations over time |
| Validate Humanity | See and judge a user's humanity using the built in Buwana user humanity score |
| Group by Bioregion | User's localize themselves by river basin.  Use this for ecological grouping and actions |


## 3. User Interface

### 3.1 Backend
- Master admins work on cpanel and phpmyadmin and with the github code

### 3.2 BAM Front End
- This will be the interface used my app admins to setup up and manage their apps

### 3.3 Buwana Front End
- Simple registration and login interface for users.
- Branding adapts to the app's client_id

## 4. Notifications

###To the Dev Team
- A built in Bug report form will send messages from users across the buwana ecosystem to our development team.

###To the BAM
- App creation success email.  Critical error notifications.

###To the Buwana User
- Account creation and login codes sent by email.

## 5. Operational Requirements

### 5.1 GitHub Connection
- We will use the Buwana repo on the Global Ecobrick Alliance's Github account for development.

## 6. Connections

### 6.1 Mailgun
- For emailing

### 6.2 Designated SMS Service
- Not yet set up.  Planned for phone number registration option (as an alternative to email)

### 6.2 ChatGPT CODES
- For deployment.

## 7. Security Requirements
- All credentials encrypted at rest.
- Authentication tokens managed with expiry logic.
- Admin access limited to verified Dev Circle members.

## 8. Open Issues
- Finalize schema for ayyew_score_tb.
- Determine external apps' access protocols.
- Develop JWT session instead of PHP sessions

## 9. Future Enhancements
- SMS notifications
- Phone notifications
- Peer user validation

## 10. Code Base

The Buwana system is developed fully in PHP, javascript, HTML and CSS.

## 11. Database Tables Overview

| Table Name | Purpose | Status |
|------------|---------|--------|
| apps_tb | App metadata and login tracking | Pending |
| ayyew_score_tb | Ayyew metrics | Pending |
| communities_tb | Community metadata | Active |
| continents_tb | Continent info | Active |
| conversations_tb | Message threading | Active |
| countries_tb | Country info | Active |
| credentials_tb | Login data | Active |
| languages_tb | Language metadata | Active |
| messages_tb | Stored messages | Active |
| message_status_tb | Message delivery/read state | Active |
| participants_tb | Conversation participants | Active |
| users_tb | User core identity and metadata | Active |
| watersheds_countries | Watershed-country links | Paused |
| watersheds_tb | Watershed metadata | Paused |

## 12 Appendix B: Nomenclature Notes
- Buwana uses suffix `_tb` for table names (e.g. `users_tb`).
- GoBrik uses prefix `tb_` for internal-only tables.
- Shared tables retain Buwana's naming style across both platforms.