# Project:

1. User can create a new help ticket.
2. User can give ticket title and description.
3. User can upload a document or image.
4. Admin can reply on help ticket.
5. Admin can reject or resolve the ticket.
6. When admin update on the ticket then user will get one notification via email that ticket status is updated.

## Tables structure:

1. Tickets table:
    - Id (primary key) {required}
    - Description (text) {required}
    - Status(open {default}, resolved, rejected) {required}
    - Attachment (string= jpg, pdf) {nullable}
    - user_id (foreign key) {required} filled by laravel
    - status_changed_by_id (foreign key) {nullable}
2. ## Replies table:
    - body (text) {required}
    - user_id {required} filled by laravel
    - ticket_id {required} filled by laravel
