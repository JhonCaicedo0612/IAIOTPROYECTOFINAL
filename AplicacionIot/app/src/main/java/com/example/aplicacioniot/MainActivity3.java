package com.example.aplicacioniot;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity3 extends AppCompatActivity {
    private TextView nameUser, accx, accy, accz, rotx, roty, rotz, pred;
    private RequestQueue cola;
    private StringRequest peticion;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main3);
        nameUser = findViewById(R.id.nameUser);
        String us = getIntent().getExtras().getString("user");
        nameUser.setText(us);
        String url = "http://10.0.2.2:5000/datosidenodoultimo?idnodo=1";
        System.out.println("la url es "+url);
        cola = Volley.newRequestQueue(this);
        peticion = new StringRequest(Request.Method.GET, url,
                this::onResponse,
                (error) -> { error.printStackTrace();}
        );
        cola.add(peticion);

        // Agregar el botón de volver
        Button btnVolver = findViewById(R.id.btnvolver);
        btnVolver.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                volver();
            }
        });
    }
    private void volver() {
        // Manejar el clic en el botón de volver (puedes llamar a finish() para cerrar la actividad)
        finish();
    }
    private void onResponse(String response) {
        try {
            JSONArray arreglo = new JSONArray(response);
            JSONObject js = arreglo.getJSONObject(0);
            String acc_x = js.getString("accx");
            String acc_y = js.getString("accy");
            String acc_z = js.getString("accz");
            String rot_x = js.getString("rotx");
            String rot_y = js.getString("roty");
            String rot_z = js.getString("rotz");
            String predi = js.getString("pred");

            accx = findViewById(R.id.ax);
            accy = findViewById(R.id.ay);
            accz = findViewById(R.id.az);
            rotx = findViewById(R.id.rx);
            roty = findViewById(R.id.ry);
            rotz = findViewById(R.id.rz);
            pred = findViewById(R.id.pred);

            accx.setText(acc_x);
            accy.setText(acc_y);
            accz.setText(acc_z);
            rotx.setText(rot_x);
            roty.setText(rot_y);
            rotz.setText(rot_z);
            pred.setText(predi);

        } catch (JSONException e) {
            throw new RuntimeException(e);
        }
    }

}
