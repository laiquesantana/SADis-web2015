<html>
<head>
  <meta charset="utf-8" />
  <title>SADis - Solicitar Aproveitamento</title>
  <link rel="stylesheet" href="css/960_24_col.css" type='text/css'/> <!-- Grid 960 -->
  <link rel="stylesheet" href="css/style.css" type='text/css' /> 
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'><!-- GoogleFonts -->
  <script src="js/jquery-1.10.2.min.js"></script>

  <link href="js/jquery-ui-1.11.2.custom/jquery-ui.css" rel="stylesheet">
  <script src="js/jquery-ui-1.11.2.custom/external/jquery/jquery.js"></script>
  <script src="js/jquery-ui-1.11.2.custom/jquery-ui.js"></script>
</head>

<script>
  $(document).ready(function() {
    $("#Nome").Setcase({caseValue : 'upper'}); 
    $('#Telefone').mask("(00)0000-0000");
    $('#CEP').mask("00.000-000");

    $("#formInscricao").validate ({ 
        ignore: "", 
        rules: 
        {
          Telefone: {required: true, minlength: 10}, 
          Nome: {required: true, minlength: 10}
          Email: {required: true, minlength: 8}, 
          Matricula: {required: true, minlength: 8}, 
        }, 
        messages: 
        { 
        Telefone: "*", 
        gdeclaracao: "*", 
        
        } 
      }); 
    });
  }
</script>

<script type="text/javascript">
  var x = 1;
  var uniList = [];
  var uniId = [];

  $(document).ready(function() {
    // Fill universities data
    getUniversities();

    var max_fields      = 10; 
    var wrapper         = $(".input_fields_wrap"); 
    var add_button      = $(".add_field_button"); 
     
    $(wrapper).on("click",".remove_field", function(e){ 
      e.preventDefault(); 
      var it = $(this).parent('div').parent('div').children();
      it = it.first();
      for (i = 1; i <= parseInt($(this).parent('div').attr("value")); i++){
        it = it.next();
      }
      for (i = parseInt($(this).parent('div').attr("value")); i < x; i++){
        var prevVal = parseInt(it.attr("value"));
        it.attr("value", prevVal-1);
        it.find(".input_field_name").attr("name", "nomeDisciplina"+(prevVal-1));
        it.find(".input_field_code").attr("name", "codigoDisciplina"+(prevVal-1));
        it.find(".input_field_ch").attr("name", "cargaHorariaDisciplina"+(prevVal-1));
        it.find(".input_field_comments").attr("name", "comentarioDisciplina"+(prevVal-1));
        it.find(".userfile").attr("name", "userfile"+(prevVal-1));
        it = it.next();
      }
      $(this).parent('div').remove(); 
      x--;
      
      $(".hidden_size").attr("value", parseInt(x));
    })

    $(add_button).click(function(e){ 
      e.preventDefault();
      if(x < max_fields){ 
        x++; 
        $(".hidden_size").attr("value", parseInt(x));

        $(wrapper).append('<div class="input_field" value="'+x+'">'
        +' Nome<font color="#FF0000">*</font>: <input class="input_field_name" type="text" name="nomeDisciplina'+x+'"/>'
        +' Código<font color="#FF0000">*</font>: <input class="input_field_code" type="text" name="codigoDisciplina'+x+'"/>'
        +' Carga Horária<font color="#FF0000">*</font>: <input class="input_field_ch" type="text" name="cargaHorariaDisciplina'+x+'"/>'
        +' <a href="#" class="remove_field"><button class="rem_field_but">Remover Disciplina</button></a>'
        +' <br>'
        +' <br>'
        +' <span class="comments">Observações: <textarea class="input_field_comments" id="input_field_comments_'+x+'" rows="6" cols="37" name="comentarioDisciplina'+x+'"></textarea></span>'
        +' <span class="upload_area">Ementa<font color="#FF0000">*</font>: <input class="userfile" name="userfile'+x+'" type="file" /></span></div>'); 
      }
    });
  });

  function getUniversities(){
    document.getElementById("countrySelect").disabled = true;
    var university = document.getElementById("countrySelect").value;

    if (university == "US"){
      alert("Pesquisando universidades dos Estados Unidos.\nPode demorar alguns minutos.");
    }

    $.getJSON("pegarUniversidades.php", {location: university}, function(json){
      // Empty previous university list
      uniList = [];
      uniId = [];

      if (json){
        if (university != "US"){
          $.each(json, function (id, elem) {
            uniList.push(elem.name);
            uniId.push(elem.id);
          });
        } else {
          $.each(json, function (id1, elem1) {
            $.each(elem1, function (id2, elem2) {
              uniList.push(elem2.name);
              uniId.push(elem2.id);
            });
          });
        }

        $("#autocomplete").autocomplete({ source: uniList });
      }

      document.getElementById("countrySelect").disabled = false;
    })
  }

    $('#Telefone').keyup(filterDigits);
    $('#Matricula').keyup(filterDigits);  
    
    function filterDigits(){
      if (/\D/g.test(this.value)){
        // Filter non-digits from input value.
        this.value = this.value.replace(/\D/g, '');
      }
    }

    function checarDisciplinas(){
      for (i = 1; i <= x; i++){
        if (($("[name='nomeDisciplina"+x+"']").val() == "") ||
            ($("[name='codigoDisciplina"+x+"']").val() == "") ||
            ($("[name='cargaHorariaDisciplina"+x+"']").val() == "") ||
            ($("[name='userfile"+x+"']").val() == "")){
          return false;
        }
      }

      return true;
    }
    
    function validar(formulario) {
      if (formulario.Nome.value.length == 0 ){
        alert("Por favor insira o nome do estudante");
        return false;
      }
      if ((formulario.Telefone.value.length == 0 )||(formulario.Telefone.value.length < 8)){
        alert("Por favor insira um telefone válido para contato. Ex: 7199999999");
        return false;
      }
      if (formulario.Email.value.length == 0 ){
        alert("Por favor insira um email para contato");
        return false;
      }
      if (formulario.Matricula.value.length == 0 ){
        alert("Por favor insira a matricula do estudante");
        return false;
      }
      if (formulario.Faculdade.value.length == 0 ){
        alert("Por favor selecione uma faculdade");
        return false;
      }
      var found = false;
      var i;
      for (i = 0; i < uniList.length; i++){
        if (uniList[i] == $("#autocomplete").val()){
          found = true;
          $("#idFacul").attr("value", uniId[i]);
          break;
        }
      }
      if (found == false) return false;
      if (formulario.CURSO.value == 0 ){
        alert("Por favor selecione um curso");
        return false;
      }
      if (!checarDisciplinas()){
        alert("Por favor preencha todos os campos marcados com (*) asterisco");
        return false;
      }

      return true;
    }

</script>


<?php 

  require_once("db.php");

?>

<body>
  <div class="background">
    <div class="container_24">
      <div class="grid_4 suffix_13">
        <div class="logo">
          <a href="index.html"><img src="logo_SADis_menor.png"></a>
        </div>
      </div>

      <div class="grid_24">
        <div class="background_transparente">    
          <div class="id_aba_ativa">
            <font color="DC792F" size="3px"style="font-weight:bold;">Solicitar Aproveitamento</font>
          </div>

          <div class="clearfix"></div> 
          <div class="background_conteudo">
            <font color="#000" face="arial, verdana, helvetica"size="2px"style="font-weight:bold;">Atenção, todos os campos com (*) asterisco, devem ser preenchidos!</font><br><br>
            <form id="formInscricao" method="POST" action="confirmar.php" onsubmit="return validar(this);"  enctype="multipart/form-data">
              
              <fieldset>
                <legend> Dados Pessoais </legend>
              <font color="#000" face="arial, verdana, helvetica"size="2px">Nome Completo</font><font color="#FF0000">*</font><br><p>  
              <input maxlength="100" style="width:350px;" type="textfield" name="Nome" id="Nome"/>           
              </br>  
              <br />
              
              <font color="#000" face="arial, verdana, helvetica"size="2px">Telefone</font><font color="#FF0000">*</font><br><p>
              <input maxlength="10" type="textfield" name="Telefone" id="Telefone"/> 
              </br>
              <br />
              
              <font color="#000" face="arial, verdana, helvetica"size="2px">E-mail</font><font color="#FF0000">*</font><br><p>
              <input maxlength="50" type="textfield" name="Email" id="Email"/> 
              </br>
              <br />
              
              <font color="#000" face="arial, verdana, helvetica"size="2px">Matrícula</font><font color="#FF0000">*</font><br><p>
              <input maxlength="10" type="textfield" name="Matricula" id="Matricula"/> 
              </br>
              <br />
              </fieldset><p>  
              <fieldset>
              <legend>Dados da Solicitação</legend>          
              
              <font color="#000" face="arial, verdana, helvetica"size="2px">País de Origem</font><font color="#FF0000">*</font><br><p>

              <select id="countrySelect" name="PAIS" onchange="getUniversities()">
                <option value="AF">Afghanistan</option>
                <option value="AL">Albania</option>
                <option value="DZ">Algeria</option>
                <option value="AS">American Samoa</option>
                <option value="AD">Andorra</option>
                <option value="AG">Angola</option>
                <option value="AI">Anguilla</option>
                <option value="AG">Antigua &amp; Barbuda</option>
                <option value="AR">Argentina</option>
                <option value="AA">Armenia</option>
                <option value="AW">Aruba</option>
                <option value="AU">Australia</option>
                <option value="AT">Austria</option>
                <option value="AZ">Azerbaijan</option>
                <option value="BS">Bahamas</option>
                <option value="BH">Bahrain</option>
                <option value="BD">Bangladesh</option>
                <option value="BB">Barbados</option>
                <option value="BY">Belarus</option>
                <option value="BE">Belgium</option>
                <option value="BZ">Belize</option>
                <option value="BJ">Benin</option>
                <option value="BM">Bermuda</option>
                <option value="BT">Bhutan</option>
                <option value="BO">Bolivia</option>
                <option value="BL">Bonaire</option>
                <option value="BA">Bosnia &amp; Herzegovina</option>
                <option value="BW">Botswana</option>
                <option value="BR" selected="selected">Brazil</option>
                <option value="BC">British Indian Ocean Ter</option>
                <option value="BN">Brunei</option>
                <option value="BG">Bulgaria</option>
                <option value="BF">Burkina Faso</option>
                <option value="BI">Burundi</option>
                <option value="KH">Cambodia</option>
                <option value="CM">Cameroon</option>
                <option value="CA">Canada</option>
                <option value="IC">Canary Islands</option>
                <option value="CV">Cape Verde</option>
                <option value="KY">Cayman Islands</option>
                <option value="CF">Central African Republic</option>
                <option value="TD">Chad</option>
                <option value="CD">Channel Islands</option>
                <option value="CL">Chile</option>
                <option value="CN">China</option>
                <option value="CI">Christmas Island</option>
                <option value="CS">Cocos Island</option>
                <option value="CO">Colombia</option>
                <option value="CC">Comoros</option>
                <option value="CG">Congo</option>
                <option value="CK">Cook Islands</option>
                <option value="CR">Costa Rica</option>
                <option value="CT">Cote D'Ivoire</option>
                <option value="HR">Croatia</option>
                <option value="CU">Cuba</option>
                <option value="CB">Curacao</option>
                <option value="CY">Cyprus</option>
                <option value="CZ">Czech Republic</option>
                <option value="DK">Denmark</option>
                <option value="DJ">Djibouti</option>
                <option value="DM">Dominica</option>
                <option value="DO">Dominican Republic</option>
                <option value="TM">East Timor</option>
                <option value="EC">Ecuador</option>
                <option value="EG">Egypt</option>
                <option value="SV">El Salvador</option>
                <option value="GQ">Equatorial Guinea</option>
                <option value="ER">Eritrea</option>
                <option value="EE">Estonia</option>
                <option value="ET">Ethiopia</option>
                <option value="FA">Falkland Islands</option>
                <option value="FO">Faroe Islands</option>
                <option value="FJ">Fiji</option>
                <option value="FI">Finland</option>
                <option value="FR">France</option>
                <option value="GF">French Guiana</option>
                <option value="PF">French Polynesia</option>
                <option value="FS">French Southern Ter</option>
                <option value="GA">Gabon</option>
                <option value="GM">Gambia</option>
                <option value="GE">Georgia</option>
                <option value="DE">Germany</option>
                <option value="GH">Ghana</option>
                <option value="GI">Gibraltar</option>
                <option value="GB">Great Britain</option>
                <option value="GR">Greece</option>
                <option value="GL">Greenland</option>
                <option value="GD">Grenada</option>
                <option value="GP">Guadeloupe</option>
                <option value="GU">Guam</option>
                <option value="GT">Guatemala</option>
                <option value="GN">Guinea</option>
                <option value="GY">Guyana</option>
                <option value="HT">Haiti</option>
                <option value="HW">Hawaii</option>
                <option value="HN">Honduras</option>
                <option value="HK">Hong Kong</option>
                <option value="HU">Hungary</option>
                <option value="IS">Iceland</option>
                <option value="IN">India</option>
                <option value="ID">Indonesia</option>
                <option value="IA">Iran</option>
                <option value="IQ">Iraq</option>
                <option value="IR">Ireland</option>
                <option value="IM">Isle of Man</option>
                <option value="IL">Israel</option>
                <option value="IT">Italy</option>
                <option value="JM">Jamaica</option>
                <option value="JP">Japan</option>
                <option value="JO">Jordan</option>
                <option value="KZ">Kazakhstan</option>
                <option value="KE">Kenya</option>
                <option value="KI">Kiribati</option>
                <option value="NK">Korea North</option>
                <option value="KS">Korea South</option>
                <option value="KW">Kuwait</option>
                <option value="KG">Kyrgyzstan</option>
                <option value="LA">Laos</option>
                <option value="LV">Latvia</option>
                <option value="LB">Lebanon</option>
                <option value="LS">Lesotho</option>
                <option value="LR">Liberia</option>
                <option value="LY">Libya</option>
                <option value="LI">Liechtenstein</option>
                <option value="LT">Lithuania</option>
                <option value="LU">Luxembourg</option>
                <option value="MO">Macau</option>
                <option value="MK">Macedonia</option>
                <option value="MG">Madagascar</option>
                <option value="MY">Malaysia</option>
                <option value="MW">Malawi</option>
                <option value="MV">Maldives</option>
                <option value="ML">Mali</option>
                <option value="MT">Malta</option>
                <option value="MH">Marshall Islands</option>
                <option value="MQ">Martinique</option>
                <option value="MR">Mauritania</option>
                <option value="MU">Mauritius</option>
                <option value="ME">Mayotte</option>
                <option value="MX">Mexico</option>
                <option value="MI">Midway Islands</option>
                <option value="MD">Moldova</option>
                <option value="MC">Monaco</option>
                <option value="MN">Mongolia</option>
                <option value="MS">Montserrat</option>
                <option value="MA">Morocco</option>
                <option value="MZ">Mozambique</option>
                <option value="MM">Myanmar</option>
                <option value="NA">Nambia</option>
                <option value="NU">Nauru</option>
                <option value="NP">Nepal</option>
                <option value="AN">Netherland Antilles</option>
                <option value="NL">Netherlands (Holland, Europe)</option>
                <option value="NV">Nevis</option>
                <option value="NC">New Caledonia</option>
                <option value="NZ">New Zealand</option>
                <option value="NI">Nicaragua</option>
                <option value="NE">Niger</option>
                <option value="NG">Nigeria</option>
                <option value="NW">Niue</option>
                <option value="NF">Norfolk Island</option>
                <option value="NO">Norway</option>
                <option value="OM">Oman</option>
                <option value="PK">Pakistan</option>
                <option value="PW">Palau Island</option>
                <option value="PS">Palestine</option>
                <option value="PA">Panama</option>
                <option value="PG">Papua New Guinea</option>
                <option value="PY">Paraguay</option>
                <option value="PE">Peru</option>
                <option value="PH">Philippines</option>
                <option value="PO">Pitcairn Island</option>
                <option value="PL">Poland</option>
                <option value="PT">Portugal</option>
                <option value="PR">Puerto Rico</option>
                <option value="QA">Qatar</option>
                <option value="ME">Republic of Montenegro</option>
                <option value="RS">Republic of Serbia</option>
                <option value="RE">Reunion</option>
                <option value="RO">Romania</option>
                <option value="RU">Russia</option>
                <option value="RW">Rwanda</option>
                <option value="NT">St Barthelemy</option>
                <option value="EU">St Eustatius</option>
                <option value="HE">St Helena</option>
                <option value="KN">St Kitts-Nevis</option>
                <option value="LC">St Lucia</option>
                <option value="MB">St Maarten</option>
                <option value="PM">St Pierre &amp; Miquelon</option>
                <option value="VC">St Vincent &amp; Grenadines</option>
                <option value="SP">Saipan</option>
                <option value="SO">Samoa</option>
                <option value="AS">Samoa American</option>
                <option value="SM">San Marino</option>
                <option value="ST">Sao Tome &amp; Principe</option>
                <option value="SA">Saudi Arabia</option>
                <option value="SN">Senegal</option>
                <option value="RS">Serbia</option>
                <option value="SC">Seychelles</option>
                <option value="SL">Sierra Leone</option>
                <option value="SG">Singapore</option>
                <option value="SK">Slovakia</option>
                <option value="SI">Slovenia</option>
                <option value="SB">Solomon Islands</option>
                <option value="OI">Somalia</option>
                <option value="ZA">South Africa</option>
                <option value="ES">Spain</option>
                <option value="LK">Sri Lanka</option>
                <option value="SD">Sudan</option>
                <option value="SR">Suriname</option>
                <option value="SZ">Swaziland</option>
                <option value="SE">Sweden</option>
                <option value="CH">Switzerland</option>
                <option value="SY">Syria</option>
                <option value="TA">Tahiti</option>
                <option value="TW">Taiwan</option>
                <option value="TJ">Tajikistan</option>
                <option value="TZ">Tanzania</option>
                <option value="TH">Thailand</option>
                <option value="TG">Togo</option>
                <option value="TK">Tokelau</option>
                <option value="TO">Tonga</option>
                <option value="TT">Trinidad &amp; Tobago</option>
                <option value="TN">Tunisia</option>
                <option value="TR">Turkey</option>
                <option value="TU">Turkmenistan</option>
                <option value="TC">Turks &amp; Caicos Is</option>
                <option value="TV">Tuvalu</option>
                <option value="UG">Uganda</option>
                <option value="UA">Ukraine</option>
                <option value="AE">United Arab Emirates</option>
                <option value="GB">United Kingdom</option>
                <option value="US">United States of America</option>
                <option value="UY">Uruguay</option>
                <option value="UZ">Uzbekistan</option>
                <option value="VU">Vanuatu</option>
                <option value="VS">Vatican City State</option>
                <option value="VE">Venezuela</option>
                <option value="VN">Vietnam</option>
                <option value="VB">Virgin Islands (Brit)</option>
                <option value="VA">Virgin Islands (USA)</option>
                <option value="WK">Wake Island</option>
                <option value="WF">Wallis &amp; Futana Is</option>
                <option value="YE">Yemen</option>
                <option value="ZR">Zaire</option>
                <option value="ZM">Zambia</option>
                <option value="ZW">Zimbabwe</option>
              </select>


              <br><font color="#000" face="arial, verdana, helvetica"size="2px">Faculdade de Origem</font><font color="#FF0000">*</font><br><p>

              <input id="autocomplete" title="Faculdade" name="Faculdade">
              <input id="idFacul" type="hidden" name="idFaculdade">
              
              </br>  
              <br />
              <font color="#000" face="arial, verdana, helvetica"size="2px">Curso Solicitado</font><font color="#FF0000">*</font><br><p>
              <select name="CURSO">
                <option value="0" selected="selected"> Selecione... </option>  
                <?php 
                  $result = mysql_query("SELECT CdIdeCur, NmIdeCur from cursos  ");
                  while($row = mysql_fetch_array($result))
                  { ?><option value="<?php echo utf8_encode($row['CdIdeCur']);?>" > <?php echo utf8_encode($row['NmIdeCur']);?> </option>             
                <?php
                  }
                ?>    
                                            
              </select>

              </br>  
              <br />

                <font color="#000" face="arial, verdana, helvetica"size="2px">Nível</font><br><p>
                <INPUT TYPE="radio" NAME="OPCAO" VALUE="op1"><font color="#000" face="arial"size="2px">Graduação</font>
                <INPUT TYPE="radio" NAME="OPCAO" VALUE="op2"><font color="#000" face="arial"size="2px">Pós-Graduação</font><p><p>
                
              <font color="#000" face="arial, verdana, helvetica"size="2px">Disciplinas Cursadas</font><font color="#FF0000">*</font>
              <button class="add_field_button"></button>
          

              <input type="hidden" class="hidden_size" name="num_files" value="1">
              <div class="input_fields_wrap">
              <div class="input_field" value="1">
                Nome<font color="#FF0000">*</font>: <input class="input_field_name" type="text" name="nomeDisciplina1">
                Código<font color="#FF0000">*</font>: <input class="input_field_code" type="text" name="codigoDisciplina1">
                Carga Horária<font color="#FF0000">*</font>: <input class="input_field_ch" type="text" name="cargaHorariaDisciplina1">
                <br>
                <br>
                <span class="comments">Observações: <textarea class="input_field_comments" id="input_field_comments_1" rows="6" cols="37" name="comentarioDisciplina1"></textarea></span>
                <span class="upload_area">Ementa<font color="#FF0000">*</font>: <input class="userfile" name="userfile1" type="file" /></span>
              </div>
              </div>

              </br>  
              </fieldset>
              
              <br />
              
                        
              <button class="but but-rc but-shadow but-primary" type="submit">enviar</button>

            </form>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>  
  </div>           
</body>