# Project Setup Instructions

Welcome! Follow the instructions below to set up and run our Laravel and Nuxt.js project using Docker.

## Prerequisites

- **Docker**: Ensure Docker and Docker Compose are installed on your system. You can install Docker from [https://docs.docker.com/get-docker/](https://docs.docker.com/get-docker/).
- **Git**: Make sure Git is installed to clone the repository.

## Project Setup

1. **Clone the Repository**

   ```bash
   git clone <repository-url>
   cd <project-folder>
   ```



2. **Docker Setup**

   - Start the Docker containers:
     ```bash
     docker-compose up -d --build
     ```
   - This command will build and start the following containers:
     - **Laravel App** (`laravel-app`): Backend server.
     - **MySQL** (`laravel-mysql`): MySQL database server.
     - **Nuxt App** (`nuxt-app`): Frontend server.

3. **Install Dependencies**

   - **Laravel** (Backend):
     ```bash
     docker exec -it laravel-app composer install
     ```
   - **Nuxt.js** (Frontend):
     ```bash
     docker exec -it nuxt-app npm install
     ```



## Access the Application

- **Laravel Backend**:

  - URL: [http://localhost:8000](http://localhost:8000)

- **Nuxt Frontend**:

  - URL: [http://localhost:3000](http://localhost:3000)

## Common Commands

- **Stopping Docker Containers**:

  ```bash
  docker-compose down
  ```

- **Restarting Docker Containers**:

  ```bash
  docker-compose up -d
  ```

- **Access a Container Shell**:

  - Laravel App:
    ```bash
    docker exec -it laravel-app sh
    ```
  - Nuxt App:
    ```bash
    docker exec -it nuxt-app sh
    ```

## Troubleshooting

- **Permission Issues**:

  - If you face permission issues, run the following inside the container:
    ```bash
    docker exec -it laravel-app chmod -R 777 storage bootstrap/cache
    ```

- **Database Issues**:

  - Make sure the MySQL container is running and accessible. You can check the logs for the MySQL container:
    ```bash
    docker logs laravel-mysql
    ```

## Notes

- Ensure that the Docker containers are using enough system resources (e.g., CPU and memory) for a smooth experience.
- If you encounter CORS errors when connecting the frontend to the backend, ensure that Laravel's CORS configuration allows requests from `http://localhost:3000`.

Feel free to reach out if you have any questions or run into issues during setup!
