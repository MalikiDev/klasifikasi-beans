@echo off
echo ========================================
echo Flask API Setup Script
echo ========================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Python is not installed or not in PATH
    echo Please install Python from https://www.python.org/downloads/
    pause
    exit /b 1
)

echo [1/7] Creating flask-api directory in D: drive...
D:
if not exist "D:\flask-api" mkdir D:\flask-api
cd D:\flask-api

echo [2/7] Copying files from Laravel project...
copy C:\Users\HP\klasifikasibeans\flask_app_dual_model.py app.py >nul
copy C:\Users\HP\klasifikasibeans\flask_requirements.txt requirements.txt >nul

echo [3/7] Creating virtual environment...
python -m venv venv

echo [4/7] Activating virtual environment...
call venv\Scripts\activate.bat

echo [5/7] Upgrading pip...
python -m pip install --upgrade pip

echo [6/7] Installing dependencies (this may take 5-10 minutes)...
echo This will download ~2-3 GB of packages...
pip install -r requirements.txt

echo [7/7] Creating models directory...
if not exist "models" mkdir models

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Flask API installed at: D:\flask-api
echo Storage used: ~2-3 GB
echo.
echo Next steps:
echo 1. Place your model files in: D:\flask-api\models\
echo    - mobilenetv3_small.h5
echo    - mobilenetv3_large.h5
echo.
echo 2. To start the server:
echo    D:
echo    cd flask-api
echo    venv\Scripts\activate
echo    python app.py
echo.
echo 3. Test the API:
echo    Open browser: http://localhost:5000/health
echo.
echo Or simply run: run_flask.bat
echo.
pause
