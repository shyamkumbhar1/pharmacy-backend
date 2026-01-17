# 🔍 CI/CD Debugging Guide

## ✅ Enhanced Debugging Features Added

### 1. **CI Workflow (`ci.yml`)**

#### Debug Steps Added:
- ✅ **Environment Info** - PHP version, Composer version, branch, commit info
- ✅ **PHP Extensions Check** - Verifies all required extensions are installed
- ✅ **Database Connection Test** - Tests MySQL connection before migrations
- ✅ **Migration Status** - Shows migration status after running
- ✅ **Final Status** - Always runs (even on failure) to show final state
- ✅ **Docker Info** - Docker version and system info
- ✅ **Build Log** - Saves build log for debugging failures

#### Verbose Logging:
- All commands now output detailed information
- Error messages are more descriptive
- Status checks at each step

---

### 2. **Deploy Workflow (`deploy.yml`)**

#### Debug Steps Added:
- ✅ **Setup Info** - Shows EC2 connection details (before connecting)
- ✅ **System Info** - OS, user, working directory
- ✅ **Pre-deployment Checks** - Docker versions, directory status
- ✅ **Container Status** - Real-time container status checks
- ✅ **Service Wait Logic** - Smart waiting with MySQL ping checks
- ✅ **Log Files** - All commands save logs:
  - `fetch.log` - Git fetch output
  - `down.log` - Container stop logs
  - `build.log` - Docker build logs
  - `composer.log` - Composer install logs
  - `keygen.log` - Key generation logs
  - `migrate.log` - Migration logs
  - `seed.log` - Seeding logs
  - `config-cache.log` - Config cache logs
  - `route-cache.log` - Route cache logs
  - `view-cache.log` - View cache logs

#### Enhanced Health Check:
- ✅ Container status check
- ✅ Recent logs display
- ✅ Detailed error output on failure
- ✅ Response body display on success

#### Enhanced Rollback:
- ✅ Shows current commit before rollback
- ✅ Shows commit after rollback
- ✅ Saves rollback build log
- ✅ Health check after rollback

---

## 🐛 Debug Mode Features

### `set -x` Enabled
- All bash commands are now traced
- Shows exact commands being executed
- Helps identify where failures occur

### Verbose Output
- All commands output detailed information
- Error messages include context
- Status checks at critical points

---

## 📋 Debug Information Available

### During CI:
1. **Environment**: PHP version, extensions, Composer version
2. **Database**: Connection status, migration status
3. **Tests**: Verbose test output
4. **Build**: Docker build logs, image info

### During Deployment:
1. **Pre-deploy**: System info, Docker versions, directory status
2. **Deploy**: Container status, build logs, service logs
3. **Post-deploy**: Health check response, container status
4. **On Failure**: Complete logs, container status, error details

---

## 🔍 How to Use Debug Info

### In GitHub Actions:
1. Go to **Actions** tab
2. Click on failed workflow
3. Expand each step to see:
   - Command output
   - Error messages
   - Debug information
   - Log files

### Key Debug Points:
- **Setup Info** - Check EC2 connection details
- **Container Status** - See if containers are running
- **Log Files** - Check specific command outputs
- **Health Check** - See API response and container logs

---

## 📊 Debug Output Examples

### Successful Deployment:
```
✅ Working directory: /var/www/pharmacy-backend
✅ Code updated
✅ .env file ready
✅ Containers started
✅ MySQL is ready
✅ Deployment complete!
✅ Health check passed!
```

### Failed Deployment:
```
⚠️ .env.example not found
⚠️ Network may already exist
⚠️ Composer install
📋 Debug Info:
[Container logs]
[Error details]
```

---

## 🛠️ Troubleshooting with Debug Info

### Issue: Deployment Failed
1. Check **Deployment Info** step - Verify EC2 connection
2. Check **Container Status** - See if containers started
3. Check **Build Log** - See Docker build errors
4. Check **Health Check** - See API response

### Issue: Tests Failing
1. Check **Environment Info** - Verify PHP/extensions
2. Check **Database Connection** - Verify MySQL connection
3. Check **Test Output** - See specific test failures

### Issue: Health Check Failed
1. Check **Container Status** - See container state
2. Check **Recent Logs** - See application errors
3. Check **Debug Info** - See complete logs

---

## ✅ Benefits

- 🔍 **Complete Visibility** - See every step in detail
- 📋 **Log Files** - All commands save logs for review
- 🐛 **Error Context** - Better error messages with context
- 📊 **Status Checks** - Real-time status at each step
- 🔄 **Failure Analysis** - Easy to identify failure points

---

## 🎯 Next Steps

1. ✅ Push code to trigger CI/CD
2. ✅ Check GitHub Actions logs
3. ✅ Review debug output
4. ✅ Use logs to troubleshoot issues

**All debugging features are now active! 🎉**
