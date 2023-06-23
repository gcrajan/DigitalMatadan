


// ------------for electionSetupModal (adminMember)-------------------------- 
var electionSetupModal = document.getElementById("electionSetupModal");

function electionSetupBtn() {
 electionSetupModal.style.display = "block";
}
function closeElectionSetupModal() {
 electionSetupModal.style.display = "none";
}

// ------------for addPartyModal (adminMember)-------------------------- 
var addPartyModal = document.getElementById("addPartyModal");
function addPartyBtn() {
  addPartyModal.style.display = "block";
}
function closeAddPartyModal() {
  addPartyModal.style.display = "none";
}


// ------------for addCandidateModal (adminMember)-------------------------- 
var addCandidateModal = document.getElementById("addCandidateModal");
function addCandidateBtn(str) {
  document.getElementById("GPID").value=str
  const x = new XMLHttpRequest()
  x.onload=()=>{
    document.getElementById("CandidateAreaSelection").innerHTML="<option value=\"\" selected disabled>--Select Area--</option>"+x.responseText
    if(x.responseText==""){alert("Candidates added in all areas");}
    else{addCandidateModal.style.display = "block";}
  }
  x.open("GET","php/candidatelist.php?GPID="+str)
  x.send()

}
function closeAddCandidateModal() {
  document.getElementById("GPID").value=""
  document.getElementById("CandidateAreaSelection").innerHTML=""

  addCandidateModal.style.display = "none";
}


window.onclick = function(event) {
  if (event.target == electionInitiateModal) {
    electionInitiateModal.style.display = "none";
  }
  if (event.target == electionSetupModal) {
    electionSetupModal.style.display = "none";
  }
  if (event.target == addPartyModal) {
    addPartyModal.style.display = "none";
  }
  if (event.target == addCandidateModal) {
    addCandidateModal.style.display = "none";
  }
}
