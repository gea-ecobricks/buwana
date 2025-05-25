
## Buwana User Authenthentication System

The Buwana system is inspired by the unique challenges of serving our global movement and maintaining our principles.  After six years of operating our own social action platform, it is clear that moving forward, we need our own account system for login access to our websites and apps so that we're not dependent on corporate services.  

Our vision is that Buwana accounts will be an open, non-proprietary credentials to login to regenerative apps.  A Buwana account will hold core community, bioregional and earth cycle data that are transferable between such apps.  Buwana accounts will not just be breach-secure but also secure from data-minining as the platform will be presided over by a not-for-profit, for-earth enterprise.  

Buwana accounts are stored and managed in a database separate from our main GoBrik app.  Our vision is that this account system will be useable by other regenerative apps as a credential management system independent of GoBrik-- yet will allow connectivity using the parrallel community, bio-region and earthen-cycles scopes that will unite the users of these apps.


Buwana database is called the the EarthenAuth_db.  Here's a breakdown of the databases structure:

| Table Name             | Purpose                                                                                              | Status  |
|-------------------------|------------------------------------------------------------------------------------------------------|---------|
| **apps_tb**            | Stores information about applications registered in the system, including registration date and login activity. | Pending Dev   |
| **ayyew_score_tb**     | Holds data related to users' Ayyew scores, including their consumption and plastic usage metrics.     | Pending Dev  |
| **communities_tb**     | Stores details of communities, such as their name, country, type, and other metadata.                | Active  |
| **continents_tb**      | Contains information about continents, including their codes, names in different languages, and geographical data. | Active  |
| **conversations_tb**   | Manages conversation data, including creation and update timestamps and associated message data.      | Active  |
| **countries_tb**       | Contains data about countries, including population, consumption rates, and geographical information. | Active  |
| **credentials_tb**     | Stores user credentials, including keys, types, and login-related data for authentication purposes.  | Active  |
| **languages_tb**       | Contains information about languages, including their names in various languages, codes, and locale data. | Active  |
| **messages_tb**        | Holds the content of messages, their associated conversation ID, and media information.              | Active  |
| **message_status_tb**  | Tracks the status of messages, including whether they are sent, delivered, or read.                  | Active  |
| **participants_tb**    | Manages participants in conversations, including their membership status and activity in specific conversations. | Active  |
| **[users_tb](Buwana-Users-Table.md)**           | Stores user information, such as personal details, login credentials, and geographical data.         | Active  |
| **watersheds_countries** | Links watersheds to the countries they span.                                                       | Paused  |
| **watersheds_tb**      | Contains information about watersheds, including their names, geographical data, and related environmental information. | Paused  |


* Nomenclature note *:  In the GoBrik table we write table names with tb_ at the start of the table name (using a plural noun i.e. ecobricks, ecobrickers, etc) if it is only used on GoBrik.  On the buwana database we add the _tb at the end of the name (using a plural nound i.e. users, countries, watershed, etc.).  On the GoBrik database, if a table is mirrored on Buwana, we name it the same as on the Buwana database (i.e. communities_tb, languages_tb, countries_tb etc.)

