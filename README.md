# Blog API

The Blog API is a Laravel-based RESTful API designed to manage blog posts and user authentication.

## Features

- User registration and login with JWT authentication.
- Create, read, update, and delete blog posts.
- Secure endpoints to ensure only authorized users can perform actions.
- Token refresh functionality for extended sessions.

## Getting Started

### Prerequisites

- [Composer](https://getcomposer.org/)
- [PHP](https://www.php.net/)

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/your-username/blog-api.git
   ```

2. Change into the project directory:

   ```bash
   cd blog-api
   ```

3. Install dependencies:

   ```bash
   composer install
   ```

4. Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

5. Generate an application key:

   ```bash
   php artisan key:generate
   ```

6. Configure your database in the `.env` file.

7. Run migrations:

   ```bash
   php artisan migrate
   ```

8. Start the development server:

   ```bash
   php artisan serve
   ```

### Usage

- Register a new user using the `/register` endpoint.
- Log in to get a JWT token using the `/login` endpoint.
- Use the token to access protected endpoints.
- Manage blog posts using `/blogs` endpoints.

## API Endpoints

### User Authentication

- **POST /register**
  - Register a new user.
  - Requires `name`, `email`, `password`, and `confirm_password`.

- **POST /login**
  - Log in and obtain a JWT token.
  - Requires `email` and `password`.

- **POST /logout**
  - Log out and invalidate the JWT token.
  - Requires a valid token in the Authorization header.

- **POST /refresh**
  - Refresh a JWT token.
  - Requires a valid token in the Authorization header.

### Blog Posts

- **GET /blogs**
  - Get a list of all blog posts.

- **GET /my-blogs**
  - Get a list of the user's blog posts.
  - Requires a valid token in the Authorization header.

- **POST /blogs**
  - Create a new blog post.
  - Requires `title` and `content`.
  - Requires a valid token in the Authorization header.

- **PUT /blogs/{blog_id}**
  - Update an existing blog post.
  - Requires `title` and `content`.
  - Requires a valid token in the Authorization header.

- **DELETE /blogs/{blog_id}**
  - Delete an existing blog post.
  - Requires a valid token in the Authorization header.

## Contributing

If you would like to contribute to the development of the Blog API, please follow our [contribution guidelines](CONTRIBUTING.md).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.