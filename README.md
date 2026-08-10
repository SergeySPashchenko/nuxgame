# NuxGame

Laravel 12 · PHP 8.2 · MySQL

Тестове завдання: реєстрація → унікальний лінк на 7 днів → сторінка A (regenerate, deactivate, ImFeelingLucky, History).  
Assignment: registration → unique 7-day link → Page A (regenerate, deactivate, ImFeelingLucky, History).

---

## Українська

### Вимоги середовища (з ТЗ)

- PHP 8.2+
- MySQL 8.0+
- Composer 2.x

Docker Compose **не** додано: оточення збігається з дефолтом ТЗ (PHP 8.2 + MySQL). Інша БД / версія PHP потребувала б `docker-compose.yaml`.

Здача — **лише з git-репозиторію** (без архівів).

### Покроковий запуск

1. **Залежності**

```bash
composer install
```

2. **Середовище**

```bash
cp .env.example .env
php artisan key:generate
```

У `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nuxgame
DB_USERNAME=root
DB_PASSWORD=
```

3. **База даних** — створити порожню MySQL БД з іменем як у `DB_DATABASE`.

4. **Міграції**

```bash
php artisan migrate
```

5. **Запуск**

```bash
php artisan serve
```

Відкрити `http://127.0.0.1:8000/` (або Valet-хост).

6. **Тести**

```bash
composer test
```

(Pest, in-memory SQLite — MySQL для тестів не потрібен.)

7. **Стиль і статичний аналіз**

```bash
composer format     # Pint — виправити
composer lint       # Pint — перевірка
composer analyse    # PHPStan / Larastan level 6
```

### Відповідність вимогам ТЗ

| Вимога ТЗ | Реалізація |
|-----------|------------|
| Форма Username, Phonenumber, Register на головній | `GET /` |
| Після Register — унікальний лінк на сторінку A | Redirect на `/page/{token}` |
| Лінк дійсний 7 днів, потім недійсний | `expires_at`, middleware → **410** |
| Перегенерувати лінк | Regenerate (transaction, новий token + 7 днів від зараз) |
| Деактивувати лінк | Deactivate (`is_active = false`) → далі 410 |
| ImFeelingLucky | Число 1–1000, Win/Lose, сума |
| Парне → Win, непарне → Lose | `GameService` |
| Сума: >900 → 70%, >600 → 50%, >300 → 30%, ≤300 → 10% | Строге `>`, `round(..., 2)` |
| History — останні 3 ImFeelingLucky | `latest()->limit(3)` по `player_id` |
| Фронт може бути простим HTML | Blade + мінімальний CSS, без JS-фреймворків |
| Отримати лінк: показати або redirect | Redirect після Register |
| YAGNI | Без Auth, Livewire, Docker Compose, зайвих шарів |

### Інтерпретація: «рандомне число от 1 до 1000»

Генерується **одне** ціле число в діапазоні **\[1; 1000\]** (обидві межі **включно**) через `random_int(1, 1000)`. Win/Lose і пороги суми (`>900` / `>600` / `>300` / `≤300`) застосовуються до цього числа.

### Можливі кроки покращення (поза ТЗ)

Усе нижче **опційно** — завдання вже виконане.

| Покращення | Що дає |
|------------|--------|
| **Обмежити / чистити `game_results`** (лишати last N на гравця) | Таблиця не росте безмежно; History лишається швидким |
| **Rate-limit** на lucky / regenerate | Захист від abuse і випадкового спаму; стабільніше навантаження на MySQL |
| **Audit trail лінків** (`deactivated_at`, `replaced_by_id`) | Підтримка / антифрод: хто який токен мав; простіший дебаг regenerate |
| **Нормалізація телефону** (E.164), за потреби unique | Чистіші дані під SMS; менше сміттєвих реєстрацій |
| **Сповіщення за 24 год до expiry** (queue / schedule) | Кращий UX, ніж тихий 410; демонстрація jobs |
| **Адмінка** (Blade / Filament) | Ops без сирого SQL; швидша реакція на інциденти |
| **Легка статистика** (aggregates + cache) | Win rate / кількість ігор без premature materialized views |
| **JSON API** для тих самих дій Page A | Кілька клієнтів (SPA / mobile) на одній доменній логіці |
| **Повний Auth** (лише з новими вимогами) | Мультидевайс без шарингу raw URL; профіль / reset |
| **Load / stress / concurrency тести** | Знати точку відмови; перевірити гонки regenerate/lucky |
| **Observability** (логи без сирого token, метрики) | Швидший дебаг у проді без витоку секретів |

Пріоритет, якщо продовжувати продукт: спочатку prune `game_results` + throttle, далі audit / stats, потім API / load-тести.

---

## English

### Environment requirements (from the brief)

- PHP 8.2+
- MySQL 8.0+
- Composer 2.x

No Docker Compose: the stack matches the assignment default (PHP 8.2 + MySQL). Another DB / PHP version would need a `docker-compose.yaml`.

Submission is **from a git repository only** (no archives).

### Step-by-step setup

1. **Dependencies**

```bash
composer install
```

2. **Environment**

```bash
cp .env.example .env
php artisan key:generate
```

Configure MySQL in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nuxgame
DB_USERNAME=root
DB_PASSWORD=
```

3. **Database** — create an empty MySQL database matching `DB_DATABASE`.

4. **Migrations**

```bash
php artisan migrate
```

5. **Run**

```bash
php artisan serve
```

Open `http://127.0.0.1:8000/` (or your Valet host).

6. **Tests**

```bash
composer test
```

(Pest, in-memory SQLite — MySQL not required for tests.)

7. **Code style / static analysis**

```bash
composer format     # Pint — fix
composer lint       # Pint — check only
composer analyse    # PHPStan / Larastan level 6
```

### Assignment requirements coverage

| Requirement | Implementation |
|-------------|----------------|
| Home form: Username, Phonenumber, Register | `GET /` |
| After Register — unique link to Page A | Redirect to `/page/{token}` |
| Link valid 7 days, then invalid | `expires_at`, middleware → **410** |
| Regenerate link | Transaction; new token; expiry = now + 7 days |
| Deactivate link | `is_active = false` → later requests 410 |
| ImFeelingLucky | Number 1–1000, Win/Lose, amount |
| Even → Win, odd → Lose | `GameService` |
| Amount: >900 → 70%, >600 → 50%, >300 → 30%, ≤300 → 10% | Strict `>`, `round(..., 2)` |
| History — last 3 ImFeelingLucky results | `latest()->limit(3)` by `player_id` |
| Frontend may be plain HTML | Blade + minimal CSS, no JS frameworks |
| Deliver link: show or redirect | Redirect after Register |
| YAGNI | No Auth, Livewire, Docker Compose, extra layers |

### Interpretation: “random number from 1 to 1000”

One integer is drawn from the closed range **\[1; 1000\]** (both ends **inclusive**) via `random_int(1, 1000)`. Win/Lose and payout thresholds (`>900` / `>600` / `>300` / `≤300`) apply to that number.

### Possible improvements (out of scope for the brief)

All optional — the assignment is already complete.

| Improvement | Benefit |
|-------------|---------|
| **Cap / prune `game_results`** (keep last N per player) | Stops unbounded table growth; keeps History cheap |
| **Rate-limit** lucky / regenerate | Abuse protection; steadier MySQL load |
| **Access-link audit trail** (`deactivated_at`, `replaced_by_id`) | Support / fraud context; easier regenerate debugging |
| **Phone normalization** (E.164), uniqueness only if product needs it | Cleaner SMS-ready data; fewer junk signups |
| **Expiry reminders** (~24h, queue / schedule) | Better UX than a silent 410; real jobs/scheduler usage |
| **Admin panel** (Blade / Filament) | Ops without raw SQL; faster incident response |
| **Lightweight stats** (aggregates + cache) | Win rate / play counts without premature materialized views |
| **JSON API** for the same Page A actions | SPA / mobile clients on one domain core |
| **Full Auth** (only with new product rules) | Multi-device access without sharing raw URLs |
| **Load / stress / concurrency tests** | Know breaking points; catch regenerate/lucky races |
| **Observability** (structured logs without raw tokens) | Faster prod debugging without leaking secrets |

If continuing as a product: start with prune + throttle, then audit / stats, then API / load testing.
