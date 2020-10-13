<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About</title>
    <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:500" rel="stylesheet">
    <link rel="stylesheet" href="static/about.css">
</head>
<body>
    <?php include 'header.php' ?>

    <div id="app">    
      <div class="container">
        <div class="panel-group panels-about" id="accordion">
          <!-- BLOCK 1 -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h1 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">1. About EU Integrity Watch</a>
              </h1>
            </div>
            <div id="collapse1" class="panel-collapse collapse in">
              <div class="panel-body">
                <p><a href="https://www.integritywatch.eu" target="_blank">EU Integrity Watch</a> is a central hub of online tools to allowing citizens, journalists and civil society to monitor the integrity of decisions made by politicians in the EU. For this purpose, data that is often scattered and difficult to access is collected, harmonised and made easily available. The platform invites users to search, rank and filter the information in an intuitive way. Empowering you to contribute in foster greater transparency, integrity and equality of access to EU decision-making and to monitor the EU institutions for potential conflicts of interest, undue influence or even corruption.</p>
                <p>The technology behind the platform (D3.js) was developed to make complex datasets accessible to a wider audience. Integrity Watch EU currently contains four different datasets on the following topics:</p>
                <ul>
                  <li>Data on the members of the European Parliament (MEPs), mainly on their outside activities and incomes.</li>
                  <li>Data on lobbying in Brussels. For the European Commission, we have combined the records of lobby meetings by senior officials with the information contained in the EU Transparency Register – the register of Brussels lobbyists. For the European Parliament, we have collected the records of lobby meetings with MEPs.</li>
                </ul>
            </div>
            </div>
          </div>
          <!-- BLOCK 2 -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">2. Introducing: Red flags</a>
              </h2>
            </div>
            <div id="collapse2" class="panel-collapse collapse in">
              <div class="panel-body">
                <p>Reflags is our latest addition to EU Integrity Watch. This new tool empowers citizens, journalists and civil society to monitor EU-funded public procurement by the EU institutions or national authorities by flagging potential risks of fraud at the beginning and at the end of the procurement procedure. Integrity Watch: Red flags automatically checks procurement documents published from the <a href="https://ted.europa.eu/TED/browse/browseByMap.do" target="_blank">EU’s Tenders Electronic Daily</a> data base using 20 risks indicators. This early warning system is designed to prevent corruption while enhancing the transparency and integrity of how public authorities buy goods, services and works on our behalf.</p>
                <p><strong>Project design and development by Chiara Girardelli & Raphaël Kergueno</strong><br />
            </div>
            </div>
          </div>
          <!-- BLOCK 3 -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">3. Corruption Risks in Public procurement</a>
              </h2>
            </div>
            <div id="collapse3" class="panel-collapse collapse in">
              <div class="panel-body">
                <p>Each year in the EU, over 250.000 public authorities spent each year <strong>2 trillion euros</strong> buying goods and services for public projects. From schools and hospitals, to power plants and dams, this means big budgets and complex plans. It also means ideal opportunities for corruption.</p>
                <p>Contracts to suppliers can be awarded without fair competition. This allows companies with political connections to triumph over their rivals. Or companies within the same industry can rig their bids, so each gets a piece of the pie. This increases the cost of services to the public. <strong>Corruption can add as much as 50 per cent to a project’s costs.</strong></p>
                <p>But corruption in public procurement isn’t just about money. It also reduces the quality of work or services. It distorts markets and deters future investments. And it can cost lives. People in many countries have paid a terrible personal price for collapsed buildings and counterfeit medicines. The end result? Our trust in our leaders is eroded.</p>
            </div>
            </div>
          </div>
          <!-- BLOCK 4 -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">4. How does it work?</a>
              </h2>
            </div>
            <div id="collapse4" class="panel-collapse collapse in">
              <div class="panel-body">
                <p>The <a href="https://www.redflags.eu/files/redflags-summary-en.pdf" target="_blank">risk indicators</a> we used for flagging the contract and contract award notices were originally defined and utilized in Hungary by <a href="https://transparency.hu/en/" target="_blank">Transparency International Hungary</a>, <a href="https://k-monitor.hu/fooldal" target="_blank">K-monitor</a> and Petabyte. Integrity Watch: Red flags combines an adapted flagging methodology with interactive and cross-filtering data visualization, using a bespoke code developed specifically for the EU DATATHON 2020.</p>
                <p>The resulting tool provides a complete overview and flags all EU-funded contract and contract award notices published on the TED database since 2017. Contract notice contain the original purchasing request of a public authority, providing key information such as nature of the goods purchased, duration of the envisioned contract, estimation of contact value as well as selection criteria. Contract award notices contain can be compared with the original notice complement with information on the winner of the tendering procedure. Using a set of bespoke indicators outlined below, these notices are flagged for potential risks, drawing attention for those warranting further scrutiny. Please note: flagged notices do not necessarily imply corruption.</p>
            </div>
            </div>
          </div>
          <!-- BLOCK 5 -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">5. Red flags indicators</a>
              </h2>
            </div>
            <div id="collapse5" class="panel-collapse collapse in">
              <div class="panel-body">
                <h3 class="subtitle-bigger">Contract Notices</h3>
                <h3>Contract can be renewed (for an indefinite or several times)</h3>
                <p>This red flag indicator is based on information on the renewability of contracts and signals situations as risky when the number of possible renewals may be considered high (3 or more), or result in a contract of a longer term (longer than 4 years). This means that the indicator does not signal an optional renewal of the contract in itself as a risk, only in qualified cases. This approach is also in line with efforts to have contracts with longer terms or factors and contractual conditions involving a more significant level of insecurity classed as risky. (The indicator is not capable of “reviewing”, signalling the lawfulness of an optional renewal set out in the contract notice.)</p>
                <h3>Contract can be renewed without any information</h3>
                <p>This red flag indicator is based on information on the renewability of contracts and signals situations where the contracting authority informs tenderers that contracts can be renewed without providing additional information such as the times it can be renewed or the total term the contract will remain valid, potentially resulting in an indefinite contract.</p> 
                <h3>Term of contract (long or indefinite)</h3>
                <p>This indicator assesses two conditions in relation to the term of public procurement contracts. On the one hand, it signals a risk when the contracting authority intends to award a contract for an indefinite term. On the other, it will identify it as a risk, if the contracting authority announces an intention to conclude a contract for a definite term longer than four years.</p> 
                <h3>Framework agreement with a single operator</h3>
                <p>Even though it is legally permissible to conclude a framework agreement with a tenderer, it still means that if the volume of goods or services to be procured is large, it will exclude competition for a longer period. Due to such situations potentially limiting competition, we are signalling a risk with the indicator. This indicator is only relevant in public procurement procedures aimed at awarding a framework agreement.</p>
                <h3>Framework agreement with several bidders - number of participants too low</h3>
                <p>This indicator signals when in a public procurement procedure aimed at awarding a framework agreement to several tenderers the contracting authority defined the minimum and/or maximum number of tenderers in less than the range of the number of tenderers including at least three tenderers and thereby restricted competition. Evidently, this indicator is only relevant in relation to awarding framework agreements, and only those to be concluded with several tenderers.</p>
                <h3>Framework agreement concluded for long period without justification</h3>
                <p>This indicator assesses two conditions in relation to the term of framework agreements. On the one hand, it signals a risk any time when the contracting authority announces an intention to conclude a framework agreement for longer than four years. A framework agreement concluded for a longer term may result in a disproportionate restriction of competition. On the other hand – exactly with respect to this exceptional case that requires some consideration –, the contracting authority has to state his reasons in the contract notice for setting a term longer than four years. The indicator also signals the breach of this safeguard, i.e. an omission of the justification. This indicator is only relevant in public procurement procedures aimed at awarding a framework agreement.</p>
                <h3>Too few evaluation criteria (less than 2 constituent factors)</h3>
                <p>This indicator signals if – when selecting the overall most favourable tender – the contracting authority gives an incomplete and therefore unlawful definition of the constituent elements necessary for using the given award criterion. The indicator currently only signals three fundamental types of incompleteness: if the constituent factors are not defined in the notice, if no more than two are defined, and if the definition of the evaluation method is basically missing. The appropriate definition of the tenders’ evaluation criteria in the contract notice is an essential prerequisite of the transparent and fair realisation of the public procurement procedure.</p>
                <h3>Accelerated procedure without justification</h3>
                <p>The use of accelerated procedure This red flag indicator checks two conditions related to types of public procurement procedures that can be accelerated. On the one hand, it signals a risk when the contracting authority opts for an accelerated procedure. The Public Procurement Act does make acceleration possible in certain cases, but whether the prerequisite of an “exceptionally justified and urgent” case truly exists is mostly trusted to the discretion of the contracting authority, and deadlines are usually quite short for candidates and tenderers. Consequently, the use of such a procedure carries a risk in itself. On the other hand – exactly with respect to this exceptional case that requires some consideration –, the contracting authority has to state his reasons in the contract notice for opting for an accelerated restricted procedure or competitive procedure.</p>
                <h3>Use of competitive negotiated procedure without justification</h3> 
                <p>This indicator signals if the contract notice does not or falsely include the grounds for the use of a negotiated procedure launched by publishing a notice. This is an infringement. Based on EU regulations, so called classical public procurement contracting authorities are not free to use negotiated procedures launched by a contract notice in accordance with general procedural rules, only when the specific conditions for these are met. The rule that the grounds for the competitive procedure have to be stated in the invitation to tender is therefore a safeguard. The absence of such statement signals a risk.</p> 
                <h3>Deadline for bids differ from date of opening</h3>
                <p>This red flag indicator signals if the opening date of the tenders or requests for participation is not identical with the expiry of the time frame for tendering or participation. The two dates must be the same with respect to the risks involved in opening tenders/requests for participation.</p> 
                <h3>Restricted procedures - number of tenderers low</h3>
                <p>This indicator signals when in restricted procedures, the contracting authority defines the minimum or maximum number of tenderers in less than the range of the number of tenderers (incl. the minimum number), thereby unlawfully restricting competition. This minimum number is no less than five in restricted procedures.</p> 
                <h3>Negotiated procedures - number of tenderers low</h3>
                <p>This indicator signals when in negotiated procedures, the contracting authority defines the minimum or maximum number of tenderers in less than the range of the number of tenderers (incl. the minimum number), thereby unlawfully restricting competition. This minimum number is no less than three in negotiated procedures.</p> 
                <h3 class="subtitle-bigger">Contract award notices</h3>
                <h3>Number of tenderers received low</h3>
                <p>This indicator is linked to the number of tenders received in the procedure, and signals a risk, if competition is at a low level in the procedure, or completely missing. Such a situation fundamentally defeats the purpose of competitive public procurement procedure. As indicators (for now) do not signal degrees of risks depending on their severity, this indicator signals less than three tenders received in a procedure (or a part thereof) as a risk, but also shows whether one or two tenders had been submitted.</p> 
                <h3>Large difference between final contract value and estimated value</h3>
                <p>This red flag indicator signals a risk when the contractual amount is defined in an overly uncertain manner in the contract notice by the contracting authority, e.g. defines the amount to be procured in a way that allows for a (positive or negative) difference of 50% or more. Procurements by contracting authorities that leave such a great margin carry the risk of making suitable, realistic tendering impossible, which makes comparability also impossible. The level of risk may depend on the object of the procurement, but the indicator is not triggered by this factor.</p>
                <h3>Successful procedure without contracting</h3>
                <p>This red flag indicator signals a risk if the public procurement procedure was successful (there is a winner), but the parties still do not conclude the contract based on the procedure. (The contracting authority has to inform also about this fact and its reasons in the notice.) The occurrence of this situation carries a significant risk, especially because the prerequisites of a relief of the contracting obligation are strict, and in such a case the entity who won the procedure is already known.</p>
                <h3>Unsuccessful procedure without statement of reasons</h3>
                <p>The red flag indicator is activated when the notice does not specify the reason for the procedure’s lack of success, even though the procedure is unsuccessful (no winner declared). This is a required safeguard in accordance with the principles of transparency and publicity that the contracting authority should provide appropriate information about the reasons why the procedure was unsuccessful.</p>
                <h3>Procedure successful without prior publication</h3>
                <p>This red flag indicator signals a risk if the contracting authority opts for a procedure without prior publication. This is also known as a negotiated procedure without prior publication. EU regulations only makes the use of this type of procedure without prior publication possible if certain conditions are met. As this type of procedure may not be used freely, and starts with the involvement of one or more tenderers called upon by the contracting authority, and its transparency is mostly ensured in retrospect on the basis of the data in the contract award notice, conducting such a procedure in itself involves risks.</p>
            </div>
            </div>
          </div>
          <!-- CONTACTS -->
          <div class="panel panel-default panel-static">
            <div class="panel-heading">
              <h2 class="panel-title">
                <a href="#">Disclaimer & Contact details</a>
              </h2>
            </div>
            <div id="contact" class="panel-collapse">
              <div class="panel-body">
                <p>All information and data used by EU Integrity Watch: Red flags are gathered from the Tenders Electronic Database (TED). We extract this information on a regular basis and publish the date of the latest update prominently on the tool. EU Integrity Watch bears no responsibility for the accuracy of the original data as we only reproduce information that is publicly available on the above-mentioned websites.</p>
                <p>The indicators are flagging potential risks. They do not necessarily imply corruption. Flagged notices warrant further scrutiny, each indicator functioning as a qualifier for understanding if the tendering procedure was completed with due diligence. The objective being to enhance the integrity of how public authorities buy goods, services and works on our behalf.</p> 
                <p>This fully functional beta version is for demonstration purposes only and made available specifically to the public for the purpose of the EU DATATHON 2020. Transparency International EU will not endorse nor encourage any republished findings until the public launch of this tool, slated for 2021.</p> 
                <p>Your support in our work is vital. As a tool designed to empower a wide audience, we welcome any feedback. Should you spot inaccuracies in the data or any functionality that does not work properly feel free to reach out directly to Chiara and Raphaël: <a href="mailto:IWredflags@gmail.com">IWredflags@gmail.com</a>.</p> 
            </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <script src="static/about.js"></script>
</body>
</html>