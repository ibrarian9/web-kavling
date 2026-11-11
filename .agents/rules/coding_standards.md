# UNIVERSAL AI CODING AGENT DIRECTIVES

## 1. Core Engineering Standards
- **Clean & Idiomatic Code:** Write production-grade, readable, and maintainable code adhering strictly to the idiomatic standards of the target language/framework.
- **Single Responsibility Principle (SRP):** Each module, class, and function must have only one reason to change. Functions must not exceed 30–40 lines.
- **Early Returns & Guard Clauses:** Avoid deep nesting (max 2 levels). Handle edge cases, invalid inputs, and error states at the top of the function to eliminate nested `if-else` trees.
- **Explicit Typing:** Enforce strict type definitions across all boundaries. Avoid `any`, `interface{}` (without type assertions), or untyped dictionaries/maps unless strictly required.
- **No Hardcoded Values:** Extract constants, status codes, magic strings, and configuration values into dedicated constant files or enums.

## 2. Architecture & Separation of Concerns (SoC)
- **Layered Architecture:** Maintain a clean boundary between:
  1. *Presentation / UI Layer:* Pure view logic, rendering, and UI-state binding.
  2. *Application / Business Logic Layer:* Services, use-cases, and business validations.
  3. *Infrastructure / Data Layer:* Database queries, API clients, caching, and third-party integrations.
- **No Cross-Contamination:** Never execute raw database queries, network calls, or heavy business logic directly inside UI components or presentation controllers.
- **Component & Module Decomposition:** Split large files once they approach 200–250 lines into smaller, focused, and reusable components/modules.

## 3. Error Handling & Resilience
- **Explicit Error Management:** Never swallow errors silently or use empty `catch`/`except` blocks.
- **Actionable Error Messages:** Provide clear, structured error logs for developers and sanitized, user-friendly messages for the client.
- **Defensive Boundary Checks:** Validate all external inputs (API payloads, query parameters, file uploads) using standard schema validators before passing data to domain logic.

## 4. Code Generation & Refactoring Guardrails
- **Scope Discipline:** Only modify or generate code directly related to the user's prompt. Do not perform unsolicited broad refactoring on untouched files.
- **Minimal Diffs:** Keep edits surgical and clean. Preserve existing formatting, linting rules, and architectural patterns of the current codebase.
- **Self-Documenting Code:** Choose clear, intention-revealing variable and function names. Write comments only to explain complex business decisions (*why*), not trivial execution steps (*how*).
- **Security Baseline:** Always sanitize inputs against SQL injection, XSS, and command injection. Never hardcode secrets, API keys, or credentials.
