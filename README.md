# Project-UpTask-MVC

UpTask is a task manager web application designed to help users organize their tasks efficiently and enhance productivity. This project utilizes PHP with MVC architecture for the backend, SASS with Gulp for styling, and Vanilla JavaScript for interactive frontend elements. Additionally, it features a REST API for seamless data communication.

## Features

- **Task Management:** Create, update, delete, and organize tasks.
- **User Authentication:** Secure registration and login functionality.
- **Responsive Design:** Accessible on various devices with a user-friendly interface.
- **API Integration:** Communicate with the REST API for data handling.

## Installation

To get UpTask running on your local machine, follow these steps:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/your-username/uptask.git
    ```

2. **Navigate into the project directory:**

    ```bash
    cd uptask
    ```

3. **Install dependencies:**

    ```bash
    npm install
    ```

4. **Compile SASS and run Gulp:**

    ```bash
    gulp
    ```

5. **Set up your database:**
   
   - Create a MySQL database.
   - Import the database schema from `database/schema.sql`.
   - Configure the database connection in `config/database.php`.

6. **Configure environment variables:**

   - Rename `.env.example` to `.env`.
   - Update `.env` with your database credentials and any other necessary configuration.

7. **Start the PHP server:**

    ```bash
    php -S localhost:8000
    ```

8. **Access UpTask:**

    Open your web browser and navigate to `http://localhost:8000`.

## Contributing

Contributions are welcome! Here's how you can contribute to this project:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/awesome-feature`).
3. Make your changes.
4. Commit your changes (`git commit -am 'Add some awesome feature'`).
5. Push to the branch (`git push origin feature/awesome-feature`).
6. Create a new Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
