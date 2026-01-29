import pandas as pd
import mysql.connector
from sklearn.naive_bayes import GaussianNB
import joblib

# === KONEKSI DATABASE ===
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",          # sesuaikan
    database="rekom_formasi"
)

query = """
SELECT
    pace_avg,
    shooting_avg,
    passing_avg,
    dribbling_avg,
    defending_avg,
    physical_avg,
    formation
FROM training_formations
"""

df = pd.read_sql(query, conn)
conn.close()

# === SPLIT FITUR & LABEL ===
X = df[['pace_avg','shooting_avg','passing_avg',
        'dribbling_avg','defending_avg','physical_avg']]
y = df['formation']

# === TRAIN MODEL ===
model = GaussianNB()
model.fit(X, y)

# === SIMPAN MODEL ===
joblib.dump(model, 'formation_model.pkl')

print("Model berhasil dilatih & disimpan.")
