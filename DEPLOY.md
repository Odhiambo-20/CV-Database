# Deployment Guide (AstonCV)

## 1. Files Added for Deployment
- `Dockerfile` - builds and runs the PHP + Apache app.
- `.dockerignore` - excludes unnecessary files from image build context.
- `.env.example` - required environment variable names.

## 2. Deploy to Railway (Recommended)

### Step 1: Push project to GitHub
```bash
git init
git add .
git commit -m "Prepare AstonCV for deployment"
git branch -M main
git remote add origin <your-github-repo-url>
git push -u origin main
```

### Step 2: Create Railway project
1. Log in to Railway.
2. Create `New Project`.
3. Choose `Deploy from GitHub repo` and select your repo.
4. Railway will detect `Dockerfile` and deploy the web service.

### Step 3: Add MySQL database
1. In the same Railway project, click `New` -> `Database` -> `MySQL`.
2. Open MySQL service `Variables` and copy values.

### Step 4: Set app environment variables
In your web service, set:
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `APP_BASE_URL` (your public app URL, optional but recommended)

These names already match `config.php`.

### Step 5: Import schema
Use Railway's MySQL connection details and run:
```bash
mysql -h <DB_HOST> -P <DB_PORT> -u <DB_USER> -p <DB_NAME> < schema.sql
```

### Step 6: Verify
Open your Railway app URL and test:
1. Register
2. Login
3. Edit CV
4. Search CV
5. View CV details

## 3. Run with Docker Locally (Optional)

### Build and run app container
```bash
docker build -t astoncv-app .
docker run --rm -p 8000:80 \
  -e DB_HOST=host.docker.internal \
  -e DB_PORT=3306 \
  -e DB_NAME=astoncv \
  -e DB_USER=root \
  -e DB_PASS= \
  -e APP_BASE_URL=http://localhost:8000 \
  astoncv-app
```

Then open:
- `http://localhost:8000/index.php`

## 4. Notes
- Ensure the database table exists before testing app features.
- Use Chrome for assessment testing.
- Keep one working test account for examiners.
