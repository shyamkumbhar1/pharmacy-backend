# 🧪 Testing Guide - Local & CI/CD

## Testing Flow

```
1. Local Test (test-local.sh)
   ↓
2. Pre-Push Hook (automatic)
   ↓
3. Code Push
   ↓
4. CI Workflow (GitHub Actions)
   - Tests
   - Build
   ↓
5. Deploy Workflow (only if CI passes)
   - Secret validation
   - Deploy to EC2
   - Health check
```

---

## Local Testing

### Run Tests Locally

```bash
cd backend
./test-local.sh
```

**What it does:**
- ✅ Checks .env file
- ✅ Installs dependencies
- ✅ Sets permissions
- ✅ Runs migrations
- ✅ Runs tests
- ✅ Code quality checks

### Pre-Push Hook (Automatic)

**Automatic testing before push:**
- Runs `test-local.sh` automatically
- Blocks push if tests fail
- Allows push if tests pass

**Disable (if needed):**
```bash
# Skip pre-push hook for one push
git push --no-verify
```

---

## CI/CD Testing

### CI Workflow

**Triggers:**
- Push to `main`, `master`, `develop`
- Pull requests

**What it does:**
- ✅ Environment setup
- ✅ Dependency installation
- ✅ Database connection test
- ✅ Migrations
- ✅ Tests
- ✅ Code quality checks
- ✅ Docker build

### Deploy Workflow

**Triggers:**
- After CI workflow completes successfully
- Manual trigger (with option to skip CI check)

**What it does:**
- ✅ Checks CI status (must pass)
- ✅ Validates secrets
- ✅ Deploys to EC2
- ✅ Health check
- ✅ Auto rollback on failure

---

## Testing Commands

### Local

```bash
# Run all tests
./test-local.sh

# Run specific tests
php artisan test

# Check code quality
php artisan config:cache
php artisan route:cache
```

### CI/CD

**Automatic:**
- Push code → CI runs automatically
- CI passes → Deploy runs automatically

**Manual:**
- GitHub → Actions → Run workflow

---

## Troubleshooting

### Local Tests Failing

```bash
# Check .env
cat .env

# Check dependencies
composer install

# Check database
php artisan migrate:status
```

### CI Tests Failing

1. Check GitHub Actions logs
2. Fix issues locally
3. Push again

### Deploy Blocked

**If CI failed:**
- Fix CI issues first
- Push again
- Deploy will run after CI passes

**Skip CI check (not recommended):**
- Manual trigger → Select "Skip CI check"
- Use only for emergency fixes

---

## Best Practices

1. ✅ **Always test locally** before push
2. ✅ **Let CI run** before production
3. ✅ **Check CI logs** if deployment blocked
4. ✅ **Don't skip CI** unless emergency
5. ✅ **Test in staging** before production

---

## Summary

- **Local:** `./test-local.sh` (before push)
- **CI:** Automatic on push (GitHub Actions)
- **Deploy:** Only after CI passes
- **Production:** Only after all tests pass

**All issues will be caught before production! 🎉**
