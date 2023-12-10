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

public class CrearNodosActivity extends AppCompatActivity {

    private EditText editTextIdNodo, editTextNombre, editTextUbicacion, editTextUser, editTextEstado;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_crear_nodos);

        editTextIdNodo = findViewById(R.id.editTextIdNodo);
        editTextNombre = findViewById(R.id.editTextNombre);
        editTextUbicacion = findViewById(R.id.editTextUbicacion);
        editTextUser = findViewById(R.id.editTextUser);
        editTextEstado = findViewById(R.id.editTextEstado);

        Button btnCrearNodo = findViewById(R.id.btnCrearNodo);
        btnCrearNodo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                crearNodo();
            }
        });
        // Agregar el botón de volver
        Button btnVolver = findViewById(R.id.btnvolver);
        btnVolver.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                volver();
            }
        });

    }

    private void crearNodo() {
        String idNodo = editTextIdNodo.getText().toString();
        String nombre = editTextNombre.getText().toString();
        String ubicacion = editTextUbicacion.getText().toString();
        String user = editTextUser.getText().toString();
        String estado = editTextEstado.getText().toString();

        if (idNodo.isEmpty() || nombre.isEmpty() || ubicacion.isEmpty() || user.isEmpty() || estado.isEmpty()) {
            Toast.makeText(this, "Por favor, completa todos los campos", Toast.LENGTH_SHORT).show();
            return;
        }

        enviarDatosAlServidor(idNodo, nombre, ubicacion, user, estado);
    }
    private void volver() {
        // Manejar el clic en el botón de volver (puedes llamar a finish() para cerrar la actividad)
        finish();
    }

    private void enviarDatosAlServidor(String idNodo, String nombre, String ubicacion, String user, String estado) {
        String url = "http://10.0.2.2:5000/crearnodo";

        // Crear un objeto JSON con los datos
        JSONObject jsonParams = new JSONObject();
        try {
            jsonParams.put("idnodo", idNodo);
            jsonParams.put("nombre", nombre);
            jsonParams.put("ubicacion", ubicacion);
            jsonParams.put("user", user);
            jsonParams.put("estado", estado);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        CrearNodoTask task = new CrearNodoTask();
        task.execute(url, jsonParams.toString());
    }

    private class CrearNodoTask extends AsyncTask<Object, Void, String> {
        @Override
        protected String doInBackground(Object... params) {
            String urlString = (String) params[0];
            String jsonData = (String) params[1];

            try {
                URL url = new URL(urlString);
                HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                connection.setRequestMethod("POST");
                connection.setRequestProperty("Content-Type", "application/json"); // Establecer el tipo de contenido como JSON
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

            return "Nodo creado exitosamente"; // Mensaje de éxito (ajustar según necesidades)
        }
        @Override
        protected void onPostExecute(String result) {
            // Manejar la respuesta del servidor según tus necesidades
            // Puedes mostrar un mensaje, navegar a otra actividad, etc.
            Toast.makeText(CrearNodosActivity.this, result, Toast.LENGTH_SHORT).show();

            // Verifica si el mensaje indica que el nodo ya existe
            if (result.toLowerCase().contains("el idnodo ya existe")) {
                // Muestra un mensaje específico
                Toast.makeText(CrearNodosActivity.this, "El nodo ya existe", Toast.LENGTH_SHORT).show();
            }
        }




    }
}
