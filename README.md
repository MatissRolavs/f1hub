# F1Hub 🏎️

A platform to explore Formula 1 data - drivers, teams, races, and results, built with Laravel, Tailwind, AnimeJS and JolpicaF1API.  

---

## ✨ Features

- Browse F1 drivers, teams, seasons, circuits, and results   
- RESTful API for data access  
- Admin dashboard  
- Responsive, modern UI  
- Clean architecture & modular code  
- Next race results prediction game
- Forum for each race to discuss previous events or talk about upcoming ones
- User authentification

---

## 🧰 Tech Stack

| Backend / Server | Laravel (PHP) |
| Frontend / UI | Vue.js / Vite / Tailwind CSS / AnimeJS |
| Database | MySQL |
| API | JolpicaF1API |

---

## 🚀 Installation & Setup

Follow these steps to get F1Hub running on your local machine.

1. **Clone the repository**

    ```bash
    git clone https://github.com/MatissRolavs/f1hub.git
    cd f1hub
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Copy environment file**

    ```bash
    cp .env.example .env
    ```

4. **Configure `.env`**

    Set database credentials, app key, etc. For example:

    ```text
    APP_NAME=F1Hub
    APP_ENV=local
    APP_KEY=base64:...  
    APP_URL=http://localhost
    
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=f1hub
    DB_USERNAME=root
    DB_PASSWORD=secret
    ```

    
5. **Run database migration**

    ```bash
    php artisan migrate
    ```

    
5. **Generate application key**

    ```bash
    php artisan key:generate
    ```


6. **Build frontend assets**

    ```bash
    npm run dev
    ```

7. **Start the server**

    ```bash
    php artisan serve
    ```

You should now be able to view the app at `http://localhost:8000`.

