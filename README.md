ABC Flight Review Website
ABC Flight Review is a website that provides a platform for users to review and share their flight experiences. This repository contains the backend code written in PHP, which exposes several API endpoints to allow remote clients to connect to a local MySQL database and interact with the flight review data.

API Endpoints
The following API endpoints are available for interacting with the flight review data:

1. GET /flights
This endpoint retrieves a list of all flights with their associated review details.

Example Response:
{
  {
    "id": 1,
    "flight_number": "ABC123",
    "airline": "XYZ Airlines",
    "origin": "New York",
    "destination": "London",
    "review": "The flight was excellent. Friendly crew and comfortable seats.",
    "rating": 4.5
  },
  {
    "id": 2,
    "flight_number": "DEF456",
    "airline": "ABC Airways",
    "origin": "Los Angeles",
    "destination": "Tokyo",
    "review": "Average flight experience. Nothing remarkable.",
    "rating": 3.0
  },
}


2. GET /flights/{id}
This endpoint retrieves detailed information about a specific flight based on its ID.

Example Response:

{
  "id": 1,
  "flight_number": "ABC123",
  "airline": "XYZ Airlines",
  "origin": "New York",
  "destination": "London",
  "review": "The flight was excellent. Friendly crew and comfortable seats.",
  "rating": 4.5,
  "timestamp": "2023-06-23 10:30:00"
}

3. POST /flights
This endpoint allows the creation of a new flight review entry.

Example Request:

{
  "flight_number": "GHI789",
  "airline": "PQR Airways",
  "origin": "Paris",
  "destination": "Rome",
  "review": "Great flight experience. Amazing service.",
  "rating": 5.0
}

4. PUT /flights/{id}
This endpoint allows updating the review details of a specific flight based on its ID.

Example Request:

{
  "review": "Updated review. Excellent service and comfortable seats."
}


5. DELETE /flights/{id}
This endpoint deletes a specific flight review based on its ID.

Setup and Usage
To set up the ABC Flight Review Website and utilize the API endpoints, follow these steps:

Clone this repository to your local machine.
Ensure you have PHP and a local MySQL database server installed.
Import the provided MySQL database schema located in the database/schema.sql file.
Update the database connection details in the config.php file with your MySQL server credentials.
Deploy the PHP code to a web server capable of executing PHP scripts.
Access the website and start utilizing the available API endpoints.
Please refer to the documentation for further details on the API usage, including request/response formats and error handling.

Contribution
Contributions to the ABC Flight Review Website are welcome! If you encounter any issues or have suggestions for improvements, please open an issue or submit a pull request. We appreciate your contributions in making this project better.

License
This project is licensed under the MIT License. Feel free to use and modify the code according to your needs.

Contact
If you have any questions or inquiries regarding this project, please contact us at victornweze97@gmail.com

Happy flying and reviewing!


