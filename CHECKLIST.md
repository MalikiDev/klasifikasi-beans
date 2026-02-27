# ✅ Setup Checklist - Coffee Roasting Classifier

## 📦 Prerequisites

```
[ ] PHP 8.2+ installed
[ ] Composer installed  
[ ] Node.js & NPM installed
[ ] Python 3.8+ installed
[ ] Git (optional)
```

## 🔧 Flask API Setup

### Step 1: Create Flask Project
```
[ ] Drive D has 5+ GB free space
[ ] Folder flask-api created at D:\flask-api
[ ] File app.py copied
[ ] File requirements.txt copied
```

### Step 2: Python Environment
```
[ ] Virtual environment created (venv) at D:\flask-api\venv
[ ] Virtual environment activated
[ ] Pip upgraded to latest version
[ ] Dependencies installed (pip install -r requirements.txt)
[ ] No installation errors
[ ] Storage used: ~2-3 GB confirmed
```

### Step 3: Test Flask
```
[ ] Server starts without errors
[ ] http://localhost:5000/health returns OK
[ ] http://localhost:5000/api/model-info returns data
[ ] No CORS errors
```

## 🎨 Laravel Setup

### Step 1: Dependencies
```
[ ] composer install completed
[ ] npm install completed
[ ] No dependency conflicts
```

### Step 2: Configuration
```
[ ] .env file exists
[ ] APP_KEY generated
[ ] FLASK_API_URL=http://localhost:5000 added
[ ] FLASK_API_TIMEOUT=60 added
[ ] Database configured (SQLite default)
```

### Step 3: Database
```
[ ] php artisan migrate completed
[ ] Table coffee_beans created
[ ] php artisan storage:link completed
[ ] storage/app/public/coffee-beans folder exists
```

### Step 4: Test Laravel
```
[ ] php artisan serve works
[ ] npm run dev works
[ ] http://localhost:8000 accessible
[ ] Redirects to /coffee page
[ ] No JavaScript errors in console
```

## 🔗 Integration Testing

### Flask API Tests
```
[ ] Health check: curl http://localhost:5000/health
[ ] Model info: curl http://localhost:5000/api/model-info
[ ] Can receive POST requests
[ ] Returns proper JSON responses
```

### Laravel-Flask Connection
```
[ ] Laravel can reach Flask API
[ ] FlaskApiService->healthCheck() returns true
[ ] No timeout errors
[ ] No CORS errors
```

### Full Workflow Test
```
[ ] Can access /coffee/create page
[ ] Can select/drag-drop image
[ ] Image preview shows
[ ] Upload button works
[ ] Classification request sent to Flask
[ ] Results received from Flask
[ ] Data saved to database
[ ] Redirected to detail page
[ ] Both model results displayed
[ ] Comparison analysis shown
```

## 🎯 Feature Checklist

### Basic Features
```
[ ] List all coffee beans (index page)
[ ] Create new entry (upload only)
[ ] View detail with dual model results
[ ] Edit entry (optional fields)
[ ] Delete entry
[ ] Reclassify with both models
```

### Dual Model Features
```
[ ] MobileNetV3-Small results displayed
[ ] MobileNetV3-Large results displayed
[ ] Agreement status shown (✓ Setuju / ⚠ Beda)
[ ] Confidence comparison visible
[ ] Processing time comparison shown
[ ] Recommendation displayed
[ ] Final classification determined
```

### UI/UX Features
```
[ ] Responsive design (mobile & desktop)
[ ] Tailwind CSS styling applied
[ ] Loading indicators work
[ ] Error messages displayed properly
[ ] Success messages displayed
[ ] Navigation works
[ ] Roasting info page accessible
```

## 🚀 Production Readiness (Optional)

### Model Integration
```
[ ] MobileNetV3-Small model file ready (.h5)
[ ] MobileNetV3-Large model file ready (.h5)
[ ] Models placed in D:\flask-api\models\
[ ] app.py updated to load real models
[ ] Preprocessing function implemented
[ ] Models load successfully at startup
[ ] Real predictions working
[ ] Model files size: ~200-500 MB
```

### Performance
```
[ ] Classification time < 5 seconds
[ ] No memory leaks
[ ] Handles multiple concurrent requests
[ ] Image size validation works
[ ] File type validation works
```

### Security
```
[ ] File upload validation
[ ] Max file size enforced (2MB)
[ ] Allowed file types only (JPG, PNG, JPEG)
[ ] CSRF protection enabled
[ ] SQL injection protected (Eloquent ORM)
[ ] XSS protection enabled
```

### Deployment (If needed)
```
[ ] Flask API deployed
[ ] Laravel deployed
[ ] Environment variables configured
[ ] Database backed up
[ ] SSL certificate installed
[ ] Domain configured
```

## 📝 Documentation

```
[ ] README.md updated
[ ] FLASK_SETUP_GUIDE.md reviewed
[ ] QUICK_START.md followed
[ ] USER_GUIDE.md available for users
[ ] API endpoints documented
```

## 🐛 Common Issues Resolved

```
[ ] Port conflicts resolved
[ ] CORS issues fixed
[ ] File permissions correct
[ ] Virtual environment activated
[ ] All services running
[ ] No 404 errors
[ ] No 500 errors
```

## 📊 Testing Scenarios

### Scenario 1: Happy Path
```
[ ] Upload valid coffee image
[ ] Both models classify correctly
[ ] Models agree on classification
[ ] High confidence scores (>80%)
[ ] Results saved successfully
[ ] Can view detail page
```

### Scenario 2: Model Disagreement
```
[ ] Upload ambiguous image
[ ] Models give different results
[ ] Disagreement status shown
[ ] Recommendation provided
[ ] Final classification determined
[ ] User can reclassify
```

### Scenario 3: Error Handling
```
[ ] Upload invalid file type → Error message
[ ] Upload too large file → Error message
[ ] Flask API down → Graceful error
[ ] Network timeout → Error message
[ ] Can retry after error
```

## 🎓 Knowledge Transfer

```
[ ] Team understands Flask API
[ ] Team understands Laravel integration
[ ] Team can add new features
[ ] Team can troubleshoot issues
[ ] Documentation is clear
```

---

## 📈 Progress Tracker

**Current Status:** _____ / _____ items completed

**Last Updated:** _____________

**Notes:**
_________________________________
_________________________________
_________________________________

---

**🎉 When all items are checked, your system is ready!**
