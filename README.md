# tweakers assessment

SQL/PHP Assessment Tweakers Comment system

Bas de Ruiter

## Installation

1. Clone the project.
2. Create virtual host that points to the /public directory.
3. Create a database and run the `database/schema.sql` script.
4. Adjust the connection details in `src/DB/Connection` to point to this database.
5. Open in browser.
 
## Unit tests

Everything is TDD built. Simply run `phpunit` from the root.

## Performance

I focused on performance when designing the CommentRepository. All the id fields in the 
database have indexes, so querying by article_id will be very fast. That is also the reason
we always include the article id in a comment, even when it's a comment on a comment.
Furthermore I fetch all data in one query. We paginate per 500 comments to avoid running
out of memory.

## Docs

An overview of the database schema can be found in the `docs` directory.
 
## Disclaimer

Since frameworks aren't allowed, I had to make decisions about how much I was going
to write myself and what was going to be out of scope. This is a list of things I
left out of scope, because they don't seem to be the focus of the exercise.

- I didn't properly escape output in the templates.
- I didn't build an MVC engine, so the controlling is done at the top of the views.
- I didn't build an ORM, but I made a system with repositories and read models.
- I didn't build an environment variables system to keep the db connection details outside git.
- The pages are minimally styled and ugly. I focused on functionality only.
- I haven't implemented forms to add data.
