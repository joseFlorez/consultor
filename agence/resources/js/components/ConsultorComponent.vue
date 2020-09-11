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
            @change="buttonsStatus"
        ></v-autocomplete>
        
        <v-row>
            <v-col
                class="d-flex align-center"
                cols="1"
                sm="1"
            >    
                Periodo
            </v-col>
            <v-col
                class="d-flex align-center"
                cols="2"
                sm="2"
            >    
                <v-select
                    v-model="d_mes"
                    class="float-left mr-2"
                    dense
                    :items="monthsFilter"
                    item-text="text"
                    item-value="num"
                    label="mês"
                    @change="buttonsStatus"
                ></v-select>
            </v-col>
            <v-col
                class="d-flex align-center"
                cols="2"
                sm="2"
            >
                <v-select
                    v-model="d_ano"
                    class="float-left mr-2"
                    dense
                    :items="[2007, 2006, 2005, 2004, 2003]"
                    label="ano"
                    @change="buttonsStatus"
                ></v-select>
            </v-col>
            <v-col
                class="d-flex align-center"
                cols="1"
                sm="1"
            >
            a
            </v-col>
            <v-col
                class="d-flex align-center"
                cols="2"
                sm="2"
            >
                <v-select
                    v-model="a_mes"
                    class="float-left mr-2"
                    dense
                    :items="monthsFilter"
                    item-text="text"
                    item-value="num"
                    label="mês"
                    @change="buttonsStatus"
                ></v-select>
            </v-col>
            <v-col
                class="d-flex align-center"
                cols="2"
                sm="2"
            >
                <v-select
                    v-model="a_ano"
                    class="float-left mr-2"
                    dense
                    :items="[2007, 2006, 2005, 2004, 2003]"
                    label="ano"
                    @change="buttonsStatus"
                ></v-select>
            </v-col>
        </v-row>
        
        <div class="my-2">
                <v-btn @click="relatorio('relatorio')" small color="primary" :disabled="button">Relatório</v-btn>
                <v-btn @click="relatorio('grafico')" small color="primary" :disabled="button">Gráfico</v-btn>
                <v-btn @click="relatorio('pizza')" small color="primary" :disabled="button">Pizza</v-btn>
        </div>

        <v-progress-linear
            indeterminate
            color="primary"
            v-if="progress"
        ></v-progress-linear>
        
        <v-simple-table
            v-if="viewData==='relatorio'"
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
                    <td>{{ calcSaldo(item.months, item, 'valor') }}</td>
                    <td>{{ calcSaldo(item.months, item, 'brut_salario') }}</td>
                    <td>{{ calcSaldo(item.months, item, 'comissao') }}</td>
                    <td>{{ calcSaldo(item.months, item, 'lucro') }}</td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>

        <grafica-component v-if="viewData==='grafico'" :items="items" :mes="a_mes"></grafica-component>
        <pizza-component v-if="viewData==='pizza'" :items="items"></pizza-component>

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
          values: [],
          monthsFilter: [
           { text: "Jan", num: '01'},
           { text: "Feb", num: '02'},
           { text: "Mar", num: '03'},
           { text: "Apr", num: '04'},
           { text: "May", num: '05'},
           { text: "Jun", num: '06'},
           { text: "Jul", num: '07'},
           { text: "Aug", num: '08'},
           { text: "Sep", num: '09'},
           { text: "Oct", num: '10'},
           { text: "Nov", num: '11'},
           { text: "Dec", num: '12'}
          ],
          d_ano: null,
          d_mes: null,
          a_ano: null,
          a_mes: null,
          viewData: null,
          button: true,
          progress: false
        }
      },
      methods: {
        formatCurrency: function(value)
        {
            return Intl.NumberFormat("pt-BR", {style: 'currency', currency: 'BRL'}).format(value);
        },
        calcSaldo: function(months, item, target)
        {
            var valor = 0;
            for(var i in months) {
                if(target != 'brut_salario'){
                    valor += months[i][target];
                } else {
                    valor += item.brut_salario;
                }
            }
            return this.formatCurrency(valor);
        },
        
        relatorio: function(view)
        {
            this.progress = true;
            this.viewData = 'x';
            var de = this.d_ano + '-' + this.d_mes + '-01';
            var a  = this.a_ano + '-' + this.a_mes + '-31';
            axios.get('consultor/relatorio?data='+this.values+'&de='+de+'&a='+a)
            .then(res => {
                this.items = res.data;
                this.viewData = view;
                this.progress = false;
            });
        },

        buttonsStatus: function() 
        {
          if(
              this.values.length > 0 && 
              this.d_ano != null && 
              this.d_mes != null && 
              this.a_ano != null &&  
              this.a_mes != null
            )  {
                this.button = false;
            } else {
                this.button = true;
            }
        }
      },
      computed: {
        //
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