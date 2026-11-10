COMPUMATCH SYSTEM ACTIVITY MONITOR — INSTALLATION

WHAT THIS ADDS
- A separate read-only Activity Monitor page at /monitoring.php.
- Successful and failed login events for client, store, and System Admin accounts.
- Important actions: inventory item added, updated, deleted, and PC build saved.
- Date/time (Philippine Time), event type, user/account, details, and source IP.
- 7-day event counters and a recent-event filter.
- The monitor is restricted to the existing authenticated System Admin session.

HOW THE SYSTEMS CONNECT
CompuMatch's existing PHP API writes event records into an activity_logs table in
its existing MySQL database. The separate monitoring page reads that same table.
No browser-side logging or extra database server is required. The table is created
automatically on the first request after the updated files are deployed.

DEPLOYMENT
1. BACK UP the currently deployed CompuMatch files and database.
2. IMPORTANT: The ZIP intentionally does NOT contain config.php. Keep your existing
   deployed htdocs/config.php in place; it contains private database/API credentials.
3. Upload/overwrite the files from the htdocs folder in this package into the
   existing deployed htdocs folder. Do not delete existing uploads or config.php.
4. Log in to the existing System Admin page, then open Activity Monitor. You can
   also visit /monitoring.php while logged in as System Admin.
5. Test: log in successfully as a client; try one invalid client password; log in
   as a store; add/update/delete an inventory item; save a PC build. Refresh the
   Activity Monitor and confirm the matching rows appear.

DATABASE
The activity_logs table is created automatically by db.php. For manual setup,
run monitoring_migration.sql in the SAME MySQL database used by CompuMatch.

EVENTS RECORDED
successful_login, failed_login, inventory_added, inventory_updated,
inventory_deleted, build_saved.

SECURITY NOTES
- The dashboard requires the existing System Admin session.
- Passwords are never stored in activity logs.
- Failed login logs contain the attempted account identifier, not the password.
- Activity logging is best-effort: a logging failure does not block normal app use.
- Logs currently retain records until manually cleaned up. Apply an appropriate
  retention period if the deployment accumulates many events.

IMPORTANT CREDENTIAL WARNING
The uploaded original archive included live database and email-service credentials
inside htdocs/config.php. Treat those credentials as exposed: rotate/change the
MySQL database password and the email API key in their provider dashboards, then
update the deployed config.php. Never upload/share config.php again. The replacement
package excludes that file by design.

DEMONSTRATION OUTLINE (2–3 MINUTES)
1. Open CompuMatch and perform a successful login (show the user account).
2. Perform one failed login with an incorrect password.
3. As a store account, add or update an inventory item (or save a client PC build).
4. Open System Admin > Activity Monitor and point out the timestamps, event labels,
   account/source, details, and IP address for each action.
5. Explain that the monitor records security events and important application actions
   in the same database used by CompuMatch.
