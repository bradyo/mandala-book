Mandala App
===========

Technologies Used
-----------------

- Vagrant
- Zend Framework 2
- Composer
- Doctrine 2 ORM
- RaphaelJS
- PhantomJS
- C3 (D3 chart library)
- jQuery + jQueryUI
- Bootstrap 3
- Guzzle
- Stripe API
- Monolog
- MongoDB
- MySQL
- Disqus
- UserEcho
- Facebook Share API
- Google Analytics

Some Techniques Used
--------------------

- Functional Modules using PSR-4 namespaces (organized by functional components)
- Dependency Injection
- Simple Analytics Engine


Database Migrations
-------------------

After editing annotations, create a new migration script and apply it:

```bash
./vendor/bin/doctrine-module migrations:diff
./vendor/bin/doctrine-module migrations:migrate
```

