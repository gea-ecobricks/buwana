# users_tb

This is the primary table for Buwana user accounts. It is linked to by the client app through `buwana_id` (for example on GoBrik will link to Buwana_id on the `tb_ecobrickers` object).

| Field                   | Type               | Description                                                                                              |
|-------------------------|--------------------|----------------------------------------------------------------------------------------------------------|
| `buwana_id`             | `int(11)`          | **Primary Key**: A unique identifier for each user in the system, with `AUTO_INCREMENT`.                 |
| `username`              | `varchar(255)`     | The user's chosen unique username. This value must be unique across the table.                           |
| `first_name`            | `varchar(255)`     | The first name of the user, stored using UTF-8 encoding.                                                 |
| `last_name`             | `varchar(255)`     | The last name of the user, stored using UTF-8 encoding.                                                  |
| `full_name`             | `varchar(255)`     | The full name of the user, combining their first and last names.                                         |
| `email`                 | `varchar(100)`     | The user's email address, used for login and communication.                                              |
| `password_hash`         | `varchar(255)`     | A hash of the user's password for authentication purposes.                                               |
| `account_status`        | `varchar(100)`     | The status of the user's account, indicating if it's active, suspended, or otherwise.                    |
| `created_at`            | `datetime`         | The date and time when the user account was created.                                                     |
| `last_login`            | `datetime`         | The date and time of the user's most recent login.                                                       |
| `role`                  | `varchar(255)`     | The role assigned to the user, defining access levels and permissions. Default is `'user'`.              |
| `failed_login_attempts` | `int(11)`          | The count of unsuccessful login attempts, used for security purposes. Default is `0`.                    |
| `password_reset_token`  | `varchar(255)`     | A token used for resetting the user's password.                                                          |
| `password_reset_expires`| `datetime`         | The expiration date and time of the password reset token.                                                |
| `password_last_reset_dt`| `datetime`         | The date and time when the user's password was last reset.                                               |
| `is_two_factor_enabled` | `tinyint(1)`       | Indicates whether two-factor authentication is enabled for the user. Default is `0`.                     |
| `brikcoin_balance`      | `decimal(10,5)`    | The balance of Brikcoins associated with the user. Default is `0.00000`.                                 |
| `gea_status`            | `varchar(255)`     | The status of the user's GEA (Global Ecobrick Alliance) membership. Default is `'null'`.                 |
| `terms_of_service`      | `tinyint(1)`       | Indicates whether the user has accepted the terms of service. Default is `0`.                            |
| `notes`                 | `text`             | Internal notes related to the user. Default is `'null'`.                                                 |
| `flagged`               | `tinyint(1)`       | Indicates if the user has been flagged for review. Default is `0`.                                       |
| `suspended`             | `tinyint(1)`       | Indicates if the user's account is suspended. Default is `0`.                                            |
| `validation_credits`    | `int(11)`          | The number of validation credits available to the user. Default is `3`.                                  |
| `profile_pic`           | `varchar(255)`     | The URL of the user's profile picture. Default is `'null'`.                                              |
| `country_id`            | `int(11)`          | **Foreign Key**: Links to the `country_id` in `countries_tb`, representing the user's country.           |
| `language_id`           | `varchar(11)`      | **Foreign Key**: Links to the `language_id` in `languages_tb`, representing the user's preferred language.|
| `earthen_newsletter_join`| `tinyint(1)`      | Indicates if the user is subscribed to the Earthen newsletter. Default is `1`.                           |
| `legacy_unactivated`    | `tinyint(1)`       | Indicates if the user's account is a legacy account that has not been activated. Default is `0`.         |
| `login_count`           | `smallint(6)`      | The number of times the user has logged into the system. Default is `0`.                                 |
| `birth_date`            | `date`             | The user's birth date.                                                                                   |
| `deleteable`            | `tinyint(1)`       | Indicates whether the user's account can be deleted. Default is `1`.                                     |
| `watershed_id`          | `int(11)`          | **Foreign Key**: Links to the `watershed_id` in `watersheds_tb`, representing the user's associated watershed. |
| `continent_code`        | `varchar(5)`       | **Foreign Key**: Links to the `continent_code` in `continents_tb`, representing the user's continent.     |
| `location_full`         | `varchar(254)`     | The user's full location, as a textual description.                                                      |
| `location_watershed`    | `varchar(254)`     | The user's location related to a specific watershed.                                                     |
| `location_lat`          | `decimal(10,8)`    | The latitude coordinate of the user's location.                                                          |
