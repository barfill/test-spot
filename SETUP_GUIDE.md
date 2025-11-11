# Quick Start - Test Spot

> **Installation time:** ~10 minutes  
> **Requirements:** Docker Desktop, Git

---

## Installation (5 steps)

### 1. Clone the repository
```bash
git clone https://github.com/barfill/test-spot.git
cd test-spot
```

### 2. Copy configuration
```bash
cp .env.example .env
```

### 3. Install PHP dependencies via Docker

**Linux/Mac:**
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

**Windows (PowerShell):**
```powershell
docker run --rm `
    -v "${PWD}:/var/www/html" `
    -w /var/www/html `
    laravelsail/php84-composer:latest `
    composer install --ignore-platform-reqs
```

### 4. Start Docker containers
```bash
# Linux/Mac
./vendor/bin/sail up -d

# Windows
.\vendor\bin\sail up -d
```

**Wait ~2 minutes** for all containers to start (PostgreSQL, Redis, etc.)

### 5. Initialize the application
```bash
# Generate application key
./vendor/bin/sail artisan key:generate

# Create database structure
./vendor/bin/sail artisan migrate

# Load sample data (optional)
./vendor/bin/sail artisan db:seed

# Install JavaScript dependencies
./vendor/bin/sail npm install

# Run dev server (in NEW terminal)
./vendor/bin/sail npm run dev
```

---

## AI Configuration (Required!)

### Get Gemini API key:
1. Go to: https://aistudio.google.com/app/apikey
2. Log in with Google account
3. Click **"Create API Key"**
4. Copy the generated key

### Add to `.env`:
```env
GEMINI_API_KEY=your_key_here
```

### Clear cache:
```bash
./vendor/bin/sail artisan config:clear
```

---

## Verify installation

### Open browser:
```
http://localhost
```
