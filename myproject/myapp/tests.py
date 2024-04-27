import requests
from django.test import TestCase


def test_api_data():
    """
    Test the API data by sending a GET request to 'http://localhost:8000/encrypt'.
    Asserts that the response status code is 200.
    """
    response = requests.get('http://localhost:8000/encrypt')
    assert response.status_code == 200