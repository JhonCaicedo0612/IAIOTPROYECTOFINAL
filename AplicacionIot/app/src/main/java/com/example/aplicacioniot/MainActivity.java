package com.example.aplicacioniot;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;
public class MainActivity extends AppCompatActivity {
    private EditText user, pass;
    private Button bot1;
    private RequestQueue cola;
    private StringRequest peticion;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        user = findViewById(R.id.user);
        pass = findViewById(R.id.pass);
        bot1 = findViewById(R.id.bot1);
        bot1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String usuario = user.getText().toString();
                String password = pass.getText().toString();
                validarUsuario(usuario,password);
                user.setText("");
                pass.setText("");
            }
        });
    }
    private void validarUsuario(String usuario, String password) {
        String url = "http://10.0.2.2:5000/validaruser";

        cola = Volley.newRequestQueue(this);

        // Create a JSONObject for the request body
        JSONObject jsonBody = new JSONObject();
        try {
            jsonBody.put("user", usuario);
            jsonBody.put("password", password);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        // Create a StringRequest with the JSON data
        peticion = new StringRequest(Request.Method.POST, url,
                this::onResponse,
                (error) -> {
                    // Handle the error
                    error.printStackTrace();
                }) {
            @Override
            public Map<String, String> getHeaders() {
                // Set the content type to 'application/json'
                Map<String, String> headers = new HashMap<>();
                headers.put("Content-Type", "application/json");
                return headers;
            }

            @Override
            public byte[] getBody() {
                // Convert the JSON body to bytes
                return jsonBody.toString().getBytes();
            }
        };

        // Add the request to the queue
        cola.add(peticion);
    }
    private void onResponse(String response) {
        try {
            JSONArray arreglo = new JSONArray(response);
            int tam = arreglo.length();
            System.out.println("el tamaño del arreglo es " + tam);
            if (tam == 0) {
                System.out.println("entre al if ");
                Toast toast = Toast.makeText(getApplicationContext(),
                        "Credenciales invalidas", Toast.LENGTH_LONG);
                toast.show();
            } else {
                System.out.println("entre al else ");
                JSONObject js = arreglo.getJSONObject(0);
                String us = js.getString("user");
                Intent intent = new Intent(MainActivity.this,
                        MainActivity4.class);
                intent.putExtra("user", us);
                startActivity(intent);
            }
        } catch (JSONException e) {
            throw new RuntimeException(e);
        }
    }
}