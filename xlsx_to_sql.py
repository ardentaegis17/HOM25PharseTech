import pandas as pd
import mysql.connector
from mysql.connector import Error
import sys

# Database connection details
host = 'localhost'
database = 'geeks'
user = 'root'
password = ''

if len(sys.argv) < 2:
    print("Usage: python xlsx_to_php.py <file.xlsx>")
    sys.exit(1)

# Path to the Excel file
excel_file = sys.argv[1]

# Read the Excel file
try:
    df = pd.read_excel(excel_file)
    print("Excel file read successfully")
    print(df.columns.tolist())
except Exception as e:
    print(f"Error reading Excel file: {e}")
    sys.exit(1)

# Connect to the MySQL database
connection = None

try:
    connection = mysql.connector.connect(
        host=host,
        database=database,
        user=user,
        password=password
    )

    if connection.is_connected():
        cursor = connection.cursor()
        print("Connected to MySQL database")

        # Iterate over the rows in the DataFrame and insert them into the database
        for index, row in df.iterrows():
            sql = """
            INSERT INTO users (user_photo, name, phone_number, email, profile_link1, profile_link2, location, specialization, sql_rank, javascript_rank, csharp_rank, java_rank, python_rank, vb_rank, cplus_rank, c_rank, ruby_rank, golang_rank, r_rank, rust_rank, gen_hobbies1, gen_hobbies2, groupings_cluster)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """
            values = (
                row['User Photo'], row['Name'], row['Phone Number'], row['Email'], row['Profile Link 1'], row['Profile Link 2'],
                row['Location'], row['Specialization'], row['SQL'], row['Java Script'], row['C#'], row['Java'], row['Python'],
                row['Visual Basic'], row['C++'], row['C'], row['Ruby'], row['Golang'], row['R'], row['Rust'], row['General Hobbies 1'],
                row['General Hobbies 2'], row['groupings_cluster']
            )
            values = tuple(None if pd.isna(value) else value for value in values)
            try:
                cursor.execute(sql, values)
            except Error as e:
                print(f"Error inserting row {index}: {e}")

        connection.commit()
        print("Data inserted successfully")

except Error as e:
    print(f"Error connecting to MySQL database: {e}")
finally:
    if connection and connection.is_connected():
        cursor.close()
        connection.close()
        print("MySQL connection closed")