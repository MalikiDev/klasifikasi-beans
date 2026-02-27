@echo off
echo ========================================
echo Starting Flask API Server
echo ========================================
echo.

D:
cd D:\flask-api

if not exist "venv" (
    echo ERROR: Virtual environment not found!
    echo Please run setup_flask.bat first
    pause
    exit /b 1
)

echo Activating virtual environment...
call venv\Scripts\activate.bat

echo Starting Flask server from D:\flask-api...
echo Server will run at: http://localhost:5000
echo Press Ctrl+C to stop
echo.

python app.py

pause
