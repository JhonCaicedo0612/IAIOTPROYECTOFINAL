package com.example.aplicacioniot;

import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class CrearRegistrosActivity extends AppCompatActivity {

    private EditText editTextUser, editTextName, editTextPassword, editTextType;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_crear_registros);

        editTextUser = findViewById(R.id.editTextUser);
        editTextName = findViewById(R.id.editTextName);
        editTextPassword = findViewById(R.id.editTextPassword);
        editTextType = findViewById(R.id.editTextType);

        Button btnCrearUsuario = findViewById(R.id.btnCrearUsuario);
        btnCrearUsuario.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                crearUsuario();
            }
        });

        // Agregar el botón de volver
        Button btnVolver = findViewById(R.id.btnVolver);
        btnVolver.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                volver();
            }
        });
    }

    private void crearUsuario() {
        String user = editTextUser.getText().toString();
        String name = editTextName.getText().toString();
        String password = editTextPassword.getText().toString();
        String type = editTextType.getText().toString();

        if (user.isEmpty() || name.isEmpty() || password.isEmpty() || type.isEmpty()) {
            Toast.makeText(this, "Por favor, completa todos los campos", Toast.LENGTH_SHORT).show();
            return;
        }

        enviarDatosAlServidor(user, name, password, type);
    }

    private void volver() {
        // Manejar el clic en el botón de volver (puedes llamar a finish() para cerrar la actividad)
        finish();
    }

    private void enviarDatosAlServidor(String user, String name, String password, String type) {
        String url = "http://10.0.2.2:5000/adduser";

        // Crear un objeto JSON con los datos
        JSONObject jsonParams = new JSONObject();
        try {
            jsonParams.put("user", user);
            jsonParams.put("name", name);
            jsonParams.put("password", password);
            jsonParams.put("type", type);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        CrearUsuarioTask task = new CrearUsuarioTask();
        task.execute(url, jsonParams.toString());
    }

    private class CrearUsuarioTask extends AsyncTask<Object, Void, String> {
        @Override
        protected String doInBackground(Object... params) {
            String urlString = (String) params[0];
            String jsonData = (String) params[1];

            try {
                URL url = new URL(urlString);
                HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                connection.setRequestMethod("POST");
                connection.setRequestProperty("Content-Type", "application/json");
                connection.setDoOutput(true);

                OutputStream outputStream = connection.getOutputStream();
                byte[] inputData = jsonData.getBytes("UTF-8");
                outputStream.write(inputData);
                outputStream.flush();
                outputStream.close();

                int responseCode = connection.getResponseCode();

                if (responseCode == HttpURLConnection.HTTP_OK) {
                    // Leer la respuesta del servidor si es necesario
                } else {
                    return "Error en la conexión. Código de respuesta: " + responseCode;
                }

            } catch (Exception e) {
                return "Excepción: " + e.getMessage();
            }

            return "Usuario creado exitosamente";
        }

        @Override
        protected void onPostExecute(String result) {
            Toast.makeText(CrearRegistrosActivity.this, result, Toast.LENGTH_SHORT).show();

            // Verifica si el mensaje indica que el usuario ya existe
            if (result.toLowerCase().contains("el usuario ya existe")) {
                // Muestra un mensaje específico
                Toast.makeText(CrearRegistrosActivity.this, "El usuario ya existe", Toast.LENGTH_SHORT).show();
            }
        }
    }
}
