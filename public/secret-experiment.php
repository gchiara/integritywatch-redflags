<html lang="en">
<head>
    <?php include 'gtag.php' ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Integrity Watch: Red Flags</title>
    <meta name="twitter:card" content="summary" />
    <meta property="og:url" content="https://www.integritywatch.eu/tenders" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Integrity Watch: Red Flags" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="" />
    <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:500" rel="stylesheet">
    <link rel="stylesheet" href="static/tenders.css">
</head>
<body>
    <div id="app" class="redflags">   
      <?php include 'header.php' ?>
      <div class="container-fluid dashboard-container-outer">
        <div class="row dashboard-container">
          <!-- ROW FOR INFO AND SHARE -->
          <div class="col-md-12">
            <div class="row">
              <!-- INFO -->
              <div class="col-md-9 chart-col" v-if="showInfo">
                <div class="boxed-container description-container">
                  <h1>Integrity Watch: Red Flags</h1>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris ante lacus, mattis eu nunc sit amet, malesuada bibendum odio. Donec mi erat, semper at volutpat et, congue quis libero. Etiam molestie pulvinar lacus a placerat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. <a href="./about.php">Read more</a></p> 
                  <i class="material-icons close-btn" @click="showInfo = false">close</i>
                </div>
              </div>
              <!-- SHARE -->
              <!--
              <div class="col-md-4 chart-col" v-if="showShare">
                <div class="boxed-container share-container">
                  <button class="twitter-btn" @click="share('twitter')">Share on Twitter</button>
                  <button class="facebook-btn" @click="share('facebook')">Share on Facebook</button>
                  <i class="material-icons close-btn" @click="showShare = false">close</i>
                </div>
              </div>
              -->
            </div>
          </div>
          <!-- MAP -->
          <div class="col-9 chart-col">
            <div class="boxed-container chart-container chart-container-table">
              <chart-header :title="charts.dotsMap.title" :info="charts.dotsMap.info" ></chart-header>
              <div class="chart-inner" id="map">
              </div>
              <div class="panel-on-map">
                <div class="chotopleth-type-buttons-container">
                  <button class="map-option-btn choropleth-type-btn active" id="choropleth-type-percent">Flagged percentage</button>
                  <button class="map-option-btn choropleth-type-btn" id="choropleth-type-amount">Number of notices</button>
                </div>
                <div class="toggle-cities-container">
                  <div class="toggle-cities-circle"></div>
                  <div class="toggle-cities-text">Cities</div>
                  <button class="map-option-btn toggle-cities-btn active">Show/hide</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-3 chart-col">
            <div class="boxed-container chart-container chart-container-table" id="map-side-panel">
              <chart-header :title="infoboxTitle" :info="'Lorem ipsum'" ></chart-header>
              <div class="selected-city-container" v-if="selectedCountry !== ''">
                <div class="selectedarea-info-box">
                    <div><strong>Tenders:</strong> {{ countriesStats[selectedCountry].tenders }}</div>
                    <div><strong>Flagged Tenders:</strong> {{ countriesStats[selectedCountry].flaggedTenders }}</div>
                    <div><strong>Total flags:</strong> {{ countriesStats[selectedCountry].totFlags }}</div>
                </div>
              </div>
              <div class="selected-city-container" v-if="selectedCity.city">
                <div class="selectedarea-info-box">
                    <div v-if="selectedCity.institutions"><strong>EU Institutions / Agencies:</strong> {{ selectedCity.institutions.length }}</div>
                    <div v-if="citiesStats[selectedCity.city]"><strong>Tenders:</strong> {{ citiesStats[selectedCity.city].tenders }}</div>
                    <div v-if="citiesStats[selectedCity.city]"><strong>Flagged Tenders:</strong> {{ citiesStats[selectedCity.city].flaggedTenders }}</div>
                    <div v-if="citiesStats[selectedCity.city]"><strong>Total flags:</strong> {{ citiesStats[selectedCity.city].totFlags }}</div>
                </div>
              </div>
              <div class="selected-city-container custom-scroll" v-if="selectedCity.city">
                <div v-for="institution in selectedCity.institutions" class="institution-list-el">
                  {{institution.OFFICIALNAME}}
                </div>
              </div>
              <div class="chart-inner" v-if="selectedCountry == '' && !selectedCity.city">
                Click on a country or city to select it
              </div>
            </div>
          </div>
          <!-- SECOND ROW -->
          <div class="col-md-5ths chart-col">
            <div class="boxed-container chart-container tenders_row2_charts">
              <chart-header :title="charts.ctype.title" :info="charts.ctype.info" ></chart-header>
              <div class="chart-inner" id="ctype_chart"></div>
            </div>
          </div>
          <div class="col-md-5ths chart-col">
            <div class="boxed-container chart-container tenders_row2_charts">
              <chart-header :title="charts.authType.title" :info="charts.authType.info" ></chart-header>
              <div class="chart-inner" id="authtype_chart"></div>
            </div>
          </div>
          <div class="col-md-5ths chart-col">
            <div class="boxed-container chart-container tenders_row2_charts">
              <chart-header :title="charts.sector.title" :info="charts.sector.info" ></chart-header>
              <div class="chart-inner" id="sector_chart"></div>
            </div>
          </div>
          <div class="col-md-5ths chart-col">
            <div class="boxed-container chart-container tenders_row2_charts">
              <chart-header :title="charts.flagsNum.title" :info="charts.flagsNum.info" ></chart-header>
              <div class="chart-inner" id="flagsnum_chart"></div>
            </div>
          </div>
          <div class="col-md-5ths chart-col">
            <div class="boxed-container chart-container tenders_row2_charts">
              <chart-header :title="charts.flagsType.title" :info="charts.flagsType.info" ></chart-header>
              <div class="chart-inner" id="flagstype_chart"></div>
            </div>
          </div>
          <!-- TABLE -->
          <div class="col-12 chart-col">
            <div class="boxed-container chart-container chart-container-table">
              <chart-header :title="charts.table.title" :info="charts.table.info" ></chart-header>
              <div class="chart-inner chart-table">
                <table class="table table-hover dc-data-table" id="dc-data-table">
                  <thead>
                    <tr class="header">
                      <th class="header">Nr</th> 
                      <th class="header">Authority Name</th>
                      <th class="header">Country</th>
                      <th class="header">Notice type and nature</th>
                      <th class="header">Title</th>
                      <th class="header">Date of publishing</th> 
                      <th class="header">Notice value</th> 
                      <th class="header">Flags</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- DETAILS MODAL -->
      <div class="modal" id="detailsModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
              <div class="modal-title">
                <div class="modal-title-ntitle" v-if="selEl.title_en">{{ selEl.title_en }}</div>
                <div class="modal-title-ntitle" v-else>{{ selEl.title_o }}</div>
                <div class="modal-title-ndate">Publication date: {{ selEl.DS }}</div>
              </div>
              <button type="button" class="close" data-dismiss="modal"><i class="material-icons">close</i></button>
            </div>
            <div v-if="selEl.flags && selEl.flags.length > 0" class="modal-flags-container">
              <div class="modal-flags-title" v-if="selEl.flags.length == 1">{{ selEl.flags.length }} flag: </div> 
              <div class="modal-flags-title" v-else>{{ selEl.flags.length }} flags: </div> 
              <div v-for="flag in selEl.flags" class="modal-flags-flag"> {{ flagsTypes[flag] }}</div>
            </div>
            <!-- Modal body -->
            <div class="modal-body modal-body-notices">
              <div class="panel-group panel-group-notice" id="accordion">
                <!-- BLOCK 1 -->
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Main Info</a>
                    </div>
                  </div>
                  <div id="collapse1" class="panel-collapse show">
                    <div class="panel-body">
                      <div v-if="pathExists('fullData.FORM_SECTION.CONTRACTING_BODY.ADDRESS_CONTRACTING_BODY.OFFICIALNAME')" class="notice-detail-container">
                        <span class="notice-detail-title">Contracting authority: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.CONTRACTING_BODY.ADDRESS_CONTRACTING_BODY.OFFICIALNAME }}</span>
                      </div>
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.CODIF_DATA.MA_MAIN_ACTIVITIES')" class="notice-detail-container">
                        <span class="notice-detail-title">Contracting authority main activities: </span><span class="notice-detail-text">{{ selEl.fullData.CODED_DATA_SECTION.CODIF_DATA.MA_MAIN_ACTIVITIES }}</span>
                      </div>
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.NOTICE_DATA.ISO_COUNTRY.@attributes.VALUE')" class="notice-detail-container">
                        <span class="notice-detail-title">Contracting authority country: </span><span class="notice-detail-text">{{ selEl.fullData.CODED_DATA_SECTION.NOTICE_DATA.ISO_COUNTRY['@attributes'].VALUE }}</span>
                      </div>
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.CODIF_DATA.PR_PROC')" class="notice-detail-container">
                        <span class="notice-detail-title">Contracting authority website: </span><span class="notice-detail-text">{{ selEl.fullData.CODED_DATA_SECTION.CODIF_DATA.PR_PROC }}</span>
                      </div>
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.NOTICE_DATA.ISO_COUNTRY.@attributes.IA_URL_GENERAL')" class="notice-detail-container">
                        <span class="notice-detail-title">Procedure type: </span><span class="notice-detail-text">{{ selEl.fullData.CODED_DATA_SECTION.NOTICE_DATA.ISO_COUNTRY['@attributes'].IA_URL_GENERAL }}</span>
                      </div>
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.CODIF_DATA.TD_DOCUMENT_TYPE')" class="notice-detail-container">
                        <span class="notice-detail-title">Document type: </span><span class="notice-detail-text">{{ selEl.fullData.CODED_DATA_SECTION.CODIF_DATA.TD_DOCUMENT_TYPE }}</span>
                      </div>
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.CODIF_DATA.NC_CONTRACT_NATURE')" class="notice-detail-container">
                        <span class="notice-detail-title">Contract type: </span><span class="notice-detail-text">{{ selEl.fullData.CODED_DATA_SECTION.CODIF_DATA.NC_CONTRACT_NATURE }}</span>
                      </div>
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.CODIF_DATA.AC_AWARD_CRIT')" class="notice-detail-container">
                        <span class="notice-detail-title">Award criteria: </span><span class="notice-detail-text">{{ selEl.fullData.CODED_DATA_SECTION.CODIF_DATA.AC_AWARD_CRIT }}</span>
                      </div>
                      <!-- tofix -->
                      <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.VAL_ESTIMATED_TOTAL')" class="notice-detail-container">
                        <span class="notice-detail-title">Estimated contract value: </span><span class="notice-detail-text">{{ addThousandsSeparator(selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.VAL_ESTIMATED_TOTAL) }} €</span>
                      </div>
                      <div v-else-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.VAL_TOTAL')" class="notice-detail-container">
                        <span class="notice-detail-title">Estimated contract value: </span><span class="notice-detail-text">{{ addThousandsSeparator(selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.VAL_TOTAL) }} €</span>
                      </div>
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.NOTICE_DATA.URI_LIST.URI_DOC')" class="notice-detail-container">
                        <span class="notice-detail-title">Original TED notice: </span><span class="notice-detail-text" v-html="findTedUrl(selEl.fullData.CODED_DATA_SECTION.NOTICE_DATA.URI_LIST.URI_DOC)"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- BLOCK 2 -->
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Object of the contract</a>
                    </div>
                  </div>
                  <div id="collapse2" class="panel-collapse collapse">
                    <div class="panel-body">
                      <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.TITLE.P')" class="notice-detail-container">
                        <span class="notice-detail-title">Contract title: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.TITLE.P }}</span>
                      </div>
                      <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.SHORT_DESCR.P')" class="notice-detail-container">
                        <span class="notice-detail-title">Short description: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.SHORT_DESCR.P.toString() }}</span>
                      </div>
                      <!-- tofix -->
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.NOTICE_DATA.VALUES.VAL_ESTIMATED_TOTAL')" class="notice-detail-container">
                        <span class="notice-detail-title">Estimated contract value: </span><span class="notice-detail-text">{{ addThousandsSeparator(selEl.fullData.CODED_DATA_SECTION.NOTICE_DATA.VALUES.VAL_ESTIMATED_TOTAL) }} €</span>
                      </div>
                      <!-- tofix -->
                      <div v-if="pathExists('fullData.CODED_DATA_SECTION.NOTICE_DATA.VALUES.VALUE')" class="notice-detail-container">
                        <span class="notice-detail-title">Estimated contract value: </span><span class="notice-detail-text">{{ addThousandsSeparator(selEl.fullData.CODED_DATA_SECTION.NOTICE_DATA.VALUES.VALUE) }} €</span>
                      </div>
                      <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.CPV_MAIN.CPV_CODE.@attributes.CODE')" class="notice-detail-container">
                        <span class="notice-detail-title">CPV code: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.CPV_MAIN.CPV_CODE['@attributes'].CODE }}</span>
                      </div>
                      <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR')" class="notice-detail-container">
                        <span class="notice-detail-title">N° of lots: </span>
                        <span class="notice-detail-text" v-if="Array.isArray(selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR)">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.length }}</span>
                        <span class="notice-detail-text" v-else>1</span>
                        <!-- Multiple lot -->
                        <div class="notice-lots-container" v-if="Array.isArray(selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR)">
                          <div class="notice-lot-box" v-for="lot in selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR">
                            <div v-if="lot.LOT_NO" class="notice-detail-container">
                              <span class="notice-detail-title">Lot number: </span><span class="notice-detail-text">{{ lot.LOT_NO }}</span>
                            </div>
                            <div v-if="lot.TITLE && lot.TITLE.P" class="notice-detail-container">
                              <span class="notice-detail-title">Lot title: </span><span class="notice-detail-text">{{ lot.TITLE.P }}</span>
                            </div>
                            <div v-if="lot.SHORT_DESCR && lot.SHORT_DESCR.P" class="notice-detail-container">
                              <span class="notice-detail-title">Lot description: </span><span class="notice-detail-text">{{ lot.SHORT_DESCR.P.toString() }}</span>
                            </div>
                            <div v-if="lot.MAIN_SITE && lot.MAIN_SITE.P" class="notice-detail-container">
                              <span class="notice-detail-title">Place of performance: </span><span class="notice-detail-text">{{ lot.MAIN_SITE.P.toString() }}</span>
                            </div>
                            <div v-if="lot.DURATION" class="notice-detail-container">
                              <span class="notice-detail-title">Contract duration: </span><span class="notice-detail-text">{{ lot.DURATION }}</span>
                            </div>
                            <div v-if="lot.RENEWAL" class="notice-detail-container">
                              <span class="notice-detail-title">Number of renewals: </span><span class="notice-detail-text">{{ lot.RENEWAL }}</span>
                            </div>
                          </div>
                        </div>
                        <!-- Single lot -->
                        <div class="notice-lot-box notice-lot-box-single" v-else>
                          <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.LOT_NO')" class="notice-detail-container">
                            <span class="notice-detail-title">Lot number: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.LOT_NO }}</span>
                          </div>
                          <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.TITLE.P')" class="notice-detail-container">
                            <span class="notice-detail-title">Lot title: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.TITLE.P }}</span>
                          </div>
                          <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.SHORT_DESCR.P')" class="notice-detail-container">
                            <span class="notice-detail-title">Lot description: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.SHORT_DESCR.P.toString() }}</span>
                          </div>
                          <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.MAIN_SITE.P')" class="notice-detail-container">
                            <span class="notice-detail-title">Place of performance: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.MAIN_SITE.P.toString() }}</span>
                          </div>
                          <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.DURATION')" class="notice-detail-container">
                            <span class="notice-detail-title">Contract duration: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.DURATION }}</span>
                          </div>
                          <div v-if="pathExists('fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.RENEWAL')" class="notice-detail-container">
                            <span class="notice-detail-title">Number of renewals: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.OBJECT_CONTRACT.OBJECT_DESCR.RENEWAL }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- BLOCK 3 -->
                <div class="panel panel-default" v-if="hasLeftiInfo(selEl)">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Legal, economical, financial, technical information</a>
                    </div>
                  </div>
                  <div id="collapse3" class="panel-collapse collapse">
                    <div class="panel-body">
                      <div v-if="pathExists('fullData.FORM_SECTION.LEFTI.PERFORMANCE_STAFF_QUALIFICATION')" class="notice-detail-container">
                        <span class="notice-detail-title">Personal situation: </span>
                        <span class="notice-detail-text" v-if="Array.isArray(selEl.fullData.FORM_SECTION.LEFTI.PERFORMANCE_STAFF_QUALIFICATION) && selEl.fullData.FORM_SECTION.LEFTI.PERFORMANCE_STAFF_QUALIFICATION.length == 0">Selection criteria as stated in the procurement documents</span>
                        <span class="notice-detail-text" v-else>{{ selEl.fullData.FORM_SECTION.LEFTI.PERFORMANCE_STAFF_QUALIFICATION }}</span>
                      </div>
                      <div v-if="pathExists('fullData.FORM_SECTION.LEFTI.ECONOMIC_CRITERIA_DOC')" class="notice-detail-container">
                        <span class="notice-detail-title">Financial ability: </span>
                        <span class="notice-detail-text" v-if="Array.isArray(selEl.fullData.FORM_SECTION.LEFTI.ECONOMIC_CRITERIA_DOC) && selEl.fullData.FORM_SECTION.LEFTI.ECONOMIC_CRITERIA_DOC.length == 0">Selection criteria as stated in the procurement documents</span>
                        <span class="notice-detail-text" v-else>{{ selEl.fullData.FORM_SECTION.LEFTI.ECONOMIC_CRITERIA_DOC }}</span>
                      </div>
                      <div v-if="pathExists('fullData.FORM_SECTION.LEFTI.TECHNICAL_CRITERIA_DOC')" class="notice-detail-container">
                        <span class="notice-detail-title">Technical capacity: </span>
                        <span class="notice-detail-text" v-if="Array.isArray(selEl.fullData.FORM_SECTION.LEFTI.TECHNICAL_CRITERIA_DOC) && selEl.fullData.FORM_SECTION.LEFTI.TECHNICAL_CRITERIA_DOC.length == 0">Selection criteria as stated in the procurement documents</span>
                        <span class="notice-detail-text" v-else>{{ selEl.fullData.FORM_SECTION.LEFTI.TECHNICAL_CRITERIA_DOC }}</span>
                      </div>
                      <div v-if="pathExists('fullData.FORM_SECTION.LEFTI.ECONOMICAL_FINANCIAL_INFO.P')" class="notice-detail-container">
                        <span class="notice-detail-title">Economic & financial info: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.LEFTI.ECONOMICAL_FINANCIAL_INFO.P }}</span>
                      </div>
                      <div v-if="pathExists('fullData.FORM_SECTION.LEFTI.TECHNICAL_PROFESSIONAL_INFO.P')" class="notice-detail-container">
                        <span class="notice-detail-title">Technical professional info: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.LEFTI.TECHNICAL_PROFESSIONAL_INFO.P }}</span>
                      </div>
                      <div v-if="pathExists('fullData.FORM_SECTION.LEFTI.PERFORMANCE_CONDITIONS.P')" class="notice-detail-container">
                        <span class="notice-detail-title">Performance conditions: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.LEFTI.PERFORMANCE_CONDITIONS.P }}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- BLOCK 4 for award noticed only -->
                <div class="panel panel-default" v-if="pathExists('fullData.FORM_SECTION.AWARD_CONTRACT')">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Contract award details</a>
                    </div>
                  </div>
                  <div id="collapse4" class="panel-collapse collapse">
                    <div class="panel-body">
                      <!-- Multiple lot -->
                      <div class="notice-lots-container" v-if="Array.isArray(selEl.fullData.FORM_SECTION.AWARD_CONTRACT)">
                        <div class="notice-lot-box" v-for="lot in selEl.fullData.FORM_SECTION.AWARD_CONTRACT">
                          <div v-if="lot.TITLE && lot.TITLE.P" class="notice-detail-container">
                            <span class="notice-detail-title">Contract title: </span><span class="notice-detail-text">{{ lot.TITLE.P.toString() }}</span>
                          </div>
                          <div v-if="lot['@attributes'] && lot['@attributes'].LOT_NO" class="notice-detail-container">
                            <span class="notice-detail-title">Lot number: </span><span class="notice-detail-text">{{ lot['@attributes'].LOT_NO }}</span>
                          </div>
                          <div v-if="pathExistsLot('AWARDED_CONTRACT.CONTRACTORS.CONTRACTOR.ADDRESS_CONTRACTOR.OFFICIALNAME',lot)" class="notice-detail-container">
                            <span class="notice-detail-title">Award winner(s): </span><span class="notice-detail-text">{{ lot.AWARDED_CONTRACT.CONTRACTORS.CONTRACTOR.ADDRESS_CONTRACTOR.OFFICIALNAME }}</span>
                          </div>
                          <div v-if="pathExistsLot('AWARDED_CONTRACT.TENDERS.NB_TENDERS_RECEIVED',lot)" class="notice-detail-container">
                            <span class="notice-detail-title">N° of tenders recieved: </span><span class="notice-detail-text">{{ lot.AWARDED_CONTRACT.TENDERS.NB_TENDERS_RECEIVED }}</span>
                          </div>
                          <div v-if="pathExistsLot('AWARDED_CONTRACT.VALUES.VAL_ESTIMATED_TOTAL',lot)" class="notice-detail-container">
                            <span class="notice-detail-title">Estimated contract value: </span><span class="notice-detail-text">{{ addThousandsSeparator(lot.AWARDED_CONTRACT.VALUES.VAL_ESTIMATED_TOTAL) }} €</span>
                          </div>
                          <div v-if="pathExistsLot('AWARDED_CONTRACT.VALUES.VAL_TOTAL',lot)" class="notice-detail-container">
                            <span class="notice-detail-title">Final contract value: </span><span class="notice-detail-text">{{ addThousandsSeparator(lot.AWARDED_CONTRACT.VALUES.VAL_TOTAL) }} €</span>
                          </div>
                          <div v-else-if="pathExistsLot('AWARDED_CONTRACT.VALUES.VAL_RANGE_TOTAL',lot)" class="notice-detail-container">
                            <span class="notice-detail-title">Final contract value: </span><span class="notice-detail-text" v-if="lot.AWARDED_CONTRACT.VALUES.VAL_RANGE_TOTAL.LOW">{{ addThousandsSeparator(lot.AWARDED_CONTRACT.VALUES.VAL_RANGE_TOTAL.LOW) }} €</span> - <span class="notice-detail-text" v-if="lot.AWARDED_CONTRACT.VALUES.VAL_RANGE_TOTAL.HIGH">{{ addThousandsSeparator(lot.AWARDED_CONTRACT.VALUES.VAL_RANGE_TOTAL.HIGH) }} €</span>
                          </div>
                          <div v-if="pathExists('fullData.FORM_SECTION.PROCEDURE.FRAMEWORK')" class="notice-detail-container">
                            <span class="notice-detail-title">Contract type: </span><span class="notice-detail-text">Framework contract</span>
                          </div>
                        </div>
                      </div>
                      <!-- Single lot -->
                      <div class="notice-lot-box notice-lot-box-single" v-else>
                        <div v-if="pathExists('fullData.FORM_SECTION.AWARD_CONTRACT.TITLE.P')" class="notice-detail-container">
                          <span class="notice-detail-title">Contract title: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.AWARD_CONTRACT.TITLE.P.toString() }}</span>
                        </div>
                        <div v-if="pathExists('fullData.FORM_SECTION.AWARD_CONTRACT.@attributes.LOT_NO')" class="notice-detail-container">
                          <span class="notice-detail-title">Lot number: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.AWARD_CONTRACT['@attributes'].LOT_NO }}</span>
                        </div>
                        <div v-if="pathExists('fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.CONTRACTORS.CONTRACTOR.ADDRESS_CONTRACTOR.OFFICIALNAME')" class="notice-detail-container">
                          <span class="notice-detail-title">Contractor(s) name: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.CONTRACTORS.CONTRACTOR.ADDRESS_CONTRACTOR.OFFICIALNAME }}</span>
                        </div>
                        <div v-if="pathExists('fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.TENDERS.NB_TENDERS_RECEIVED')" class="notice-detail-container">
                          <span class="notice-detail-title">N° of tenders recieved: </span><span class="notice-detail-text">{{ selEl.fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.TENDERS.NB_TENDERS_RECEIVED }}</span>
                        </div>
                        <div v-if="pathExists('fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.VALUES.VAL_ESTIMATED_TOTAL')" class="notice-detail-container">
                          <span class="notice-detail-title">Estimated contract value: </span><span class="notice-detail-text">{{ addThousandsSeparator(selEl.fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.VALUES.VAL_ESTIMATED_TOTAL) }} €</span>
                        </div>
                        <div v-if="pathExists('fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.VALUES.VAL_TOTAL')" class="notice-detail-container">
                          <span class="notice-detail-title">Final contract value: </span><span class="notice-detail-text">{{ addThousandsSeparator(selEl.fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.VALUES.VAL_TOTAL) }} €</span>
                        </div>
                        <div v-if="pathExists('fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.VALUES.VAL_RANGE_TOTAL')" class="notice-detail-container">
                          <span class="notice-detail-title">Final contract value: </span><span class="notice-detail-text">{{ addThousandsSeparator(selEl.fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.VALUES.VAL_RANGE_TOTAL.LOW) }} €</span> - <span class="notice-detail-text">{{ addThousandsSeparator(selEl.fullData.FORM_SECTION.AWARD_CONTRACT.AWARDED_CONTRACT.VALUES.VAL_RANGE_TOTAL.HIGH) }} €</span>
                        </div>
                        <div v-if="pathExists('fullData.FORM_SECTION.PROCEDURE.FRAMEWORK')" class="notice-detail-container">
                          <span class="notice-detail-title">Contract type: </span><span class="notice-detail-text">Framework contract</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- BLOCK 5 -->
                <div class="panel panel-default" v-if="pathExists('fullData.FORM_SECTION.COMPLEMENTARY_INFO.INFO_ADD.P')">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">Complementary information</a>
                    </div>
                  </div>
                  <div id="collapse5" class="panel-collapse collapse">
                    <div class="panel-body">
                      <div class="notice-detail-container">
                        <span class="notice-detail-title">Complementary information: </span>
                        <span class="notice-detail-text" v-if="Array.isArray(selEl.fullData.FORM_SECTION.COMPLEMENTARY_INFO.INFO_ADD.P)">{{ selEl.fullData.FORM_SECTION.COMPLEMENTARY_INFO.INFO_ADD.P.join(", ") }}</span>
                        <span class="notice-detail-text" v-else>{{ selEl.fullData.FORM_SECTION.COMPLEMENTARY_INFO.INFO_ADD.P }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Bottom bar -->
      <div class="container-fluid footer-bar">
        <div class="row">
          <div class="footer-col col-8 col-sm-4">
            <div class="footer-input">
              <input type="text" id="search-input" placeholder="Filter…">
              <i class="material-icons">search</i>
            </div>
          </div>
          <div class="footer-col col-4 col-sm-8 footer-counts">
            <div class="dc-data-count count-box count-box-tenders">
              <div class="filter-count">0</div>out of <strong class="total-count">0</strong> notices
            </div>
          </div>
        </div>
        <!-- Reset filters -->
        <button class="reset-btn"><i class="material-icons">settings_backup_restore</i><span class="reset-btn-text">Reset filters</span></button>
      </div>
      <!-- Loader -->
      <loader v-if="loader" :text="'Lorem Ipsum'" />
    </div>

    <script type="text/javascript" src="vendor/js/d3.v5.min.js"></script>
    <script type="text/javascript" src="vendor/js/crossfilter.min.js"></script>
    <script type="text/javascript" src="vendor/js/dc.js"></script>
    <script type="text/javascript" src="vendor/js/topojson.v1.min.js"></script>
    <script type="text/javascript" src="vendor/js/d3-geo-projection.v2.min.js"></script>
    <script type="text/javascript" src="vendor/js/d3-scale-chromatic.v0.3.min.js"></script>

    <script src="static/tenders-eu.js"></script>

 
</body>
</html>