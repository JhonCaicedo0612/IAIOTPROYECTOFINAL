package com.example.aplicacioniot;

import android.os.Bundle;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
public class MainActivity2 extends AppCompatActivity {
    private TextView nameUser, accx, accy, accz, rotx, roty, rotz, pred;
    private RequestQueue cola;
    private StringRequest peticion;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main2);
        nameUser = findViewById(R.id.nameUser);
        String us = getIntent().getExtras().getString("user");
        nameUser.setText("Datos del nodo del usuario: "+us);
        String url = "http://10.0.2.2:5000/datosidenodoultimo?idnodo=";
        System.out.println("la url es "+url);
        cola = Volley.newRequestQueue(this);
        peticion = new StringRequest(Request.Method.GET, url,
                this::onResponse,
                (error) -> { error.printStackTrace();}
        );
        cola.add(peticion);
    }
    private void onResponse(String response) {
        try {
            JSONArray arreglo = new JSONArray(response);
            JSONObject js = arreglo.getJSONObject(0);
            String acc_x = js.getString("acc_x");
            String acc_y = js.getString("acc_y");
            String acc_z = js.getString("acc_z");
            String rot_x = js.getString("rot_x");
            String rot_y = js.getString("rot_y");
            String rot_z = js.getString("rot_z");
            String prediccion = js.getString("prediccion");
            accx = findViewById(R.id.accx);
            accy = findViewById(R.id.accy);
            accz = findViewById(R.id.accz);
            rotx = findViewById(R.id.rotx);
            roty = findViewById(R.id.roty);
            rotz = findViewById(R.id.rotz);
            pred = findViewById(R.id.pred);
            accx.setText("La aceleracion en x es: "+ acc_x);
            accy.setText("La aceleracion en y es: "+ acc_y);
            accz.setText("La aceleracion en z es: "+ acc_z);
            rotx.setText("La rotacion en x es: "+ rot_x);
            roty.setText("La rotacion en y es: "+ rot_y);
            rotz.setText("La rotacion en z es: "+ rot_z);
            pred.setText("la prediccion es "+ prediccion);
        } catch (JSONException e) {
            throw new RuntimeException(e);
        }
    }
}

