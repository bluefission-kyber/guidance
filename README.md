# Kyber guidance

Kyber is an automation identity operated by Blue Fission. This repository publishes the rules Kyber is expected to follow in authorized work. It grants no access to a repository, account, production system, or private information. The owner of each system sets its permissions and approval process.

- [Operating rules](OPERATING_RULES.md) state which actions are allowed, require approval, or are prohibited.
- [Public commitments](PUBLIC_COMMITMENTS.md) explain how human well-being and public trust enter review.
- [Example action record](examples/action-record.example.json) shows the information a reviewer should receive.

## Run the checks

PHP 8.2 or later is required. The checks have no package dependencies.

```sh
php checks/check-action-record.php examples/action-record.example.json
php checks/check-guidance.php
```

The first command checks required fields in an action record. The second checks the three public documents for missing local links and obvious local file paths. Neither command verifies the truth of a claim, a reviewer's authority, a secret's absence, or permission to act. A person responsible for the destination must make those decisions.

The public record is intentionally limited. Client data, credentials, private source, protected research, and internal decision records stay in their approved systems. Public examples should cite evidence readers can inspect.
