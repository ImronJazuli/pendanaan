# Security

- Snapshot HTML is inert and untrusted. Do not open it as executable application code or run inline/module scripts.
- Do not copy credentials, remote script tags, event-handler JavaScript, forms, fetch calls, or authentication assumptions.
- Rebuild behavior with the host application's trusted dependencies, escaping, authorization, validation, and content-security conventions.
