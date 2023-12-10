package com.example.aplicacioniot;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

public class MainActivity4 extends AppCompatActivity {
    private TextView nameUser;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main4);
        nameUser = findViewById(R.id.nameUser);
        String us = getIntent().getExtras().getString("user");
        nameUser.setText(us);

        // Obtén referencias a los botones
        Button verRegistrosButton = findViewById(R.id.button3);
        Button crearNodosButton = findViewById(R.id.button2);
        Button crearRegistrosButton = findViewById(R.id.button);

        // Configura listeners para los botones
        verRegistrosButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                // Inicia la actividad para ver registros
                Intent intent = new Intent(MainActivity4.this, MainActivity3.class);
                intent.putExtra("user", us);

                startActivity(intent);
            }
        });

        crearNodosButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                // Inicia la actividad para crear nodos
                Intent intent = new Intent(MainActivity4.this, CrearNodosActivity.class);
                intent.putExtra("user", us);

                startActivity(intent);
            }
        });

        crearRegistrosButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                // Inicia la actividad para crear registros
                Intent intent = new Intent(MainActivity4.this, CrearRegistrosActivity.class);
                intent.putExtra("user", us);

                startActivity(intent);
            }
        });

    }

}
