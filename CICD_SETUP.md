# CI/CD Setup Guide - Backend

## ✅ Updated Workflows

### 1. **CI Workflow** (`ci.yml`)
- ✅ Automated testing
- ✅ Code quality checks
- ✅ Docker build verification
- ✅ Runs on push/PR

### 2. **Deploy Workflow** (`deploy.yml`)
- ✅ First-time EC2 setup (optional)
- ✅ Automated deployment
- ✅ Health check after deploy
- ✅ Auto rollback on failure
- ✅ Runs on push to main/master

### 3. **Health Check Workflow** (`health-check.yml`)
- ✅ Automated monitoring (every 5 minutes)
- ✅ Auto restart on failure
- ✅ Manual trigger available

### 4. **Backup Workflow** (`backup.yml`)
- ✅ Daily database backup (2 AM UTC)
- ✅ Automatic cleanup (keeps 7 days)
- ✅ Manual trigger available

---

## 🔧 GitHub Secrets Setup

**Required Secrets:**
1. `EC2_HOST` - Your EC2 instance IP (e.g., 13.203.154.21)
2. `EC2_USER` - EC2 username (usually `ubuntu`)
3. `SSH_PRIVATE_KEY` - Your `.pem` file content (complete file)
4. `EC2_PORT` - SSH port (default: 22)
5. `REPO_URL` - Repository URL (optional, for first-time setup)

**How to add SSH_PRIVATE_KEY:**
```bash
# On local machine
cat ~/your-key.pem
# Copy complete content including:
# -----BEGIN RSA PRIVATE KEY-----
# ...content...
# -----END RSA PRIVATE KEY-----
# Paste in GitHub Secrets
```

---

## 🚀 Usage

### First Time Setup

1. **Add GitHub Secrets** (see above)

2. **Run Setup Workflow:**
   - Go to GitHub → Actions → Deploy workflow
   - Click "Run workflow"
   - Check "First time EC2 setup?"
   - Click "Run workflow"

3. **Wait 15-20 minutes** for setup to complete

### Regular Deployment

**Automatic:**
- Push code to `main` or `master` branch
- CI/CD automatically runs:
  1. Tests
  2. Build
  3. Deploy
  4. Health check

**Manual:**
- GitHub → Actions → Deploy workflow → Run workflow

---

## 📊 Workflow Status

Check workflow status:
- GitHub → Actions tab
- See all workflow runs
- Click on any run to see logs

---

## 🔍 Monitoring

### Health Check
- Runs automatically every 5 minutes
- Checks: `http://localhost:8000/api/health`
- Auto-restarts on failure

### Backups
- Daily at 2 AM UTC
- Location: `/var/backups/pharmacy/`
- Keeps last 7 days

---

## 🛠️ Troubleshooting

### Deployment Failed
1. Check GitHub Actions logs
2. SSH to EC2: `ssh -i key.pem ubuntu@EC2_IP`
3. Check logs: `cd /var/www/pharmacy-backend && docker-compose logs`

### Health Check Failed
1. Check backend logs: `docker-compose logs app`
2. Check nginx logs: `docker-compose logs nginx`
3. Restart manually: `docker-compose restart`

### SSH Connection Failed
1. Verify `SSH_PRIVATE_KEY` format (must include BEGIN/END lines)
2. Check EC2 security group (port 22 open)
3. Verify EC2_IP is correct

---

## 📝 Workflow Files

```
backend/.github/workflows/
├── ci.yml              # Testing & Build
├── deploy.yml          # Deployment
├── health-check.yml    # Health Monitoring
└── backup.yml          # Database Backup
```

---

## ✅ Next Steps

1. ✅ Add GitHub Secrets
2. ✅ Test first deployment (manual run)
3. ✅ Verify health check works
4. ✅ Test automatic deployment (push code)

---

## 🎯 Benefits

- ✅ 100% Automated
- ✅ Zero Manual Steps
- ✅ Auto Health Monitoring
- ✅ Auto Rollback
- ✅ Daily Backups
- ✅ Production Ready

---

**Setup Complete! 🎉**
