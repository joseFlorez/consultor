<template>
    <v-app> 
      <v-app-bar
        color="primary"
        dark
        fixed
        clipped-left
        app
        elevation="2"
      >
        <v-toolbar-title>Agence</v-toolbar-title>
      </v-app-bar>
        <v-main>
        <v-container>
        <v-autocomplete
            v-model="values"
            :items="consultores"
            dense
            chips
            small-chips
            label="Consultores"
            item-text="no_usuario"
            item-value="co_usuario"
            multiple
            @change="viewValues()"
        ></v-autocomplete>
        <div class="my-2">
            <v-btn @click="relatorio()" small color="primary">Relatório</v-btn>
            <v-btn @click="relatorio()" small color="primary">Gráfico</v-btn>
            <v-btn @click="relatorio()" small color="primary">Pizza</v-btn>
        </div>
        <v-simple-table
            dense
            v-for="item in items"
            :key="item.no_usuario"
        >
            <template v-slot:default>
                <thead>    
                <tr style="background-color: rgb(226, 220, 220);">
                    <td colspan="5">{{ item.no_usuario }}</td>
                </tr>
                <tr>
                    <td>Periodo</td>
                    <td class="text-left">Receita Líquida</td>
                    <td class="text-left">Custo fixo</td>
                    <td class="text-left">Comissao</td>
                    <td class="text-left">Lucro</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(i, index) in item.months">
                    <td>{{ index }}</td>
                    <td>{{ formatCurrency(i.valor) }}</td>
                    <td>{{ formatCurrency(item.brut_salario) }}</td>
                    <td>{{ formatCurrency(i.comissao) }}</td>
                    <td>{{ formatCurrency(i.lucro) }}</td>
                </tr>
                <tr>
                    <td>SALDO</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>
        </v-container>
        </v-main>
    </v-app>
</template>
  
  <script>
    const axios = require('axios').default;

    export default {
      data () {
        return {
          items: null,
          consultores: [],
          values: []
        }
      },
      methods: {
        formatCurrency: function(value)
        {
            return Intl.NumberFormat("pt-BR", {style: 'currency', currency: 'BRL'}).format(value);
        },
        calcSaldo: function()
        {
            return ;
        },
        viewValues: function()
        {
            console.log(this.values);
        },
        relatorio: function()
        {
            axios.get('consultor/relatorio')
            .then(res => {
                this.items = res.data;
            });
        }
      },
      computed: {
        business: function() 
        {
          //return this.$store.state.user.business;
        }
      },
      created: function() {
        //
      },
      mounted: function()
      {
        axios.get('consultor/list')
        .then(res => {
            this.consultores = res.data;
        });
      }
  
    }
  </script>