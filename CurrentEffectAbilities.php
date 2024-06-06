<?php


//Return 1 if the effect should be removed
function EffectHitEffect($cardID)
{
  global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $defPlayer, $mainPlayer, $CCS_WeaponIndex, $combatChain, $CCS_DamageDealt;
  $attackID = $combatChain[0];
  switch($cardID) {
    case "6954704048"://Heroic Sacrifice
      $ally = new Ally(AttackerMZID($mainPlayer), $mainPlayer);
      WriteLog("Heroic Sacrifice defeated " . CardLink($ally->CardID(), $ally->CardID()));
      $ally->Destroy();
      break;
    case "8988732248-1"://Rebel Assault
      AddCurrentTurnEffect("8988732248-2", $mainPlayer);
      break;
    case "0802973415"://Outflank
      AddCurrentTurnEffect("0802973415-1", $mainPlayer);
      break;
    case "6514927936-1"://Leia Organa
      AddCurrentTurnEffectFromCombat("6514927936-2", $mainPlayer);
      break;
    default:
      break;
  }
  return 0;
}

//Return true if there's a chained action
function FinalizeChainLinkEffects()
{
  global $mainPlayer, $currentTurnEffects;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnPieces()) {
    switch($currentTurnEffects[$i]) {
      case "8988732248-2"://Rebel Assault
        PrependDecisionQueue("SWAPTURN", $mainPlayer, "-");
        PrependDecisionQueue("ELSE", $mainPlayer, "-");
        PrependDecisionQueue("MZOP", $mainPlayer, "ATTACK", 1);
        PrependDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        PrependDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a unit to attack with");
        PrependDecisionQueue("MZFILTER", $mainPlayer, "status=1");
        PrependDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYALLY:trait=Rebel");
        return true;
      case "0802973415-1"://Outflank
        PrependDecisionQueue("SWAPTURN", $mainPlayer, "-");
        PrependDecisionQueue("ELSE", $mainPlayer, "-");
        PrependDecisionQueue("MZOP", $mainPlayer, "ATTACK", 1);
        PrependDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        PrependDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a unit to attack with");
        PrependDecisionQueue("MZFILTER", $mainPlayer, "status=1");
        PrependDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYALLY");
        return true;
      case "6514927936-2"://Leia Organa
        PrependDecisionQueue("SWAPTURN", $mainPlayer, "-");
        PrependDecisionQueue("ELSE", $mainPlayer, "-");
        PrependDecisionQueue("MZOP", $mainPlayer, "ATTACK", 1);
        PrependDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        PrependDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a unit to attack with");
        PrependDecisionQueue("MZFILTER", $mainPlayer, "status=1");
        PrependDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYALLY:trait=Rebel");
        return true;
      case "87e8807695"://Leia Organa - Leader Unit
        SearchCurrentTurnEffects("87e8807695", $mainPlayer, remove:true);
        PrependDecisionQueue("SWAPTURN", $mainPlayer, "-");
        PrependDecisionQueue("ELSE", $mainPlayer, "-");
        PrependDecisionQueue("MZOP", $mainPlayer, "ATTACK", 1);
        PrependDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        PrependDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a unit to attack with");
        PrependDecisionQueue("MZFILTER", $mainPlayer, "status=1");
        PrependDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYALLY:trait=Rebel");
        return true;
      case "9560139036"://Ezra Bridger
        SearchCurrentTurnEffects("9560139036", $mainPlayer, remove:true);
        PrependDecisionQueue("MODAL", $mainPlayer, "EZRABRIDGER", 1);
        PrependDecisionQueue("SHOWMODES", $mainPlayer, $cardID, 1);
        PrependDecisionQueue("MULTICHOOSETEXT", $mainPlayer, "1-Leave,Play,Discard-1");
        PrependDecisionQueue("SETDQCONTEXT", $mainPlayer, "The top card is <0>; Choose a mode for Ezra Bridger");
        PrependDecisionQueue("SETDQVAR", $mainPlayer, "0");
        PrependDecisionQueue("DECKCARDS", $mainPlayer, "0");
        return true;
      default: break;
    }
  }
  return false;
}

function EffectAttackModifier($cardID)
{
  global $mainPlayer;
  $params = explode("_", $cardID);
  if(count($params) == 1) {
    $params = explode("-", $cardID);
  }
  $cardID = $params[0];
  if(count($params) > 1) $subparam = $params[1];
  switch($cardID)
  {
    case "2587711125": return -4;//Disarm
    case "2569134232": return -4;//Jedha City
    case "1323728003": return -1;//Electrostaff
    case "2651321164": return 2;//Tactical Advantage
    case "1701265931": return 4;//Moment of Glory
    case "1900571801": return 2;//Overwhelming Barrage
    case "3809048641": return 3;//Surprise Strike
    case "3038238423": return 2;//Fleet Lieutenant
    case "3208391441": return -2;//Make an Opening
    case "6432884726": return 2;//Steadfast Battalion
    case "8244682354": return -1;//Jyn Erso
    case "8600121285": return 1;//IG-88
    case "6954704048": return 2;//Heroic Sacrifice
    case "20f21b4948": return -1;//Jyn Erso
    case "9097690846": return 2;//Snowtrooper Lieutenant
    case "9210902604"://Precision Fire
      $attacker = new Ally(AttackerMZID($mainPlayer), $mainPlayer);
      return TraitContains($attacker->CardID(), "Trooper", $mainPlayer) ? 2 : 0;
    case "8297630396": return 1;//Shoot First
    case "5464125379": return -2;//Strafing Gunship
    case "8495694166": return -2;//Jedi Lightsaber
    case "3789633661": return 4;//Cunning
    case "1939951561": return $subparam;//Attack Pattern Delta
    case "8988732248": return 1;//Rebel Assault
    case "7109944284": return -1* $subparam;//Luke Skywalker
    case "1885628519": return 1;//Crosshair
    case "1480894253": return 2;//Kylo Ren
    case "2503039837": return IsAllyAttackTarget() ? 1 : 0;//Moff Gideon Leader
    case "4534554684": return 2;//Freetown Backup
    case "4721657243": return 3;//Kihraxz Heavy Fighter
    case "7171636330": return -4;//Chain Code Collector
    case "2526288781": return 1;//Bossk
    default: return 0;
  }
}

function EffectHasBlockModifier($cardID)
{
  switch($cardID)
  {
    default: return false;
  }
}

function EffectBlockModifier($cardID, $index)
{
  global $combatChain, $defPlayer, $mainPlayer;
  switch($cardID) {

    default:
      return 0;
  }
}

function RemoveEffectsOnChainClose()
{

}

function OnAttackEffects($attack)
{
  global $currentTurnEffects, $mainPlayer, $defPlayer;
  $attackType = CardType($attack);
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch($currentTurnEffects[$i]) {

        default:
          break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
}

function CurrentEffectBaseAttackSet($cardID)
{
  global $currentPlayer, $currentTurnEffects;
  $mod = -1;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    if($currentTurnEffects[$i + 1] == $currentPlayer && IsCombatEffectActive($currentTurnEffects[$i])) {
      switch($currentTurnEffects[$i]) {

        default: break;
      }
    }
  }
  return $mod;
}

function CurrentEffectCostModifiers($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $CS_PlayUniqueID;
  $costModifier = 0;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {
        case "5707383130"://Bendu
          if(!AspectContains($cardID, "Heroism", $currentPlayer) && !AspectContains($cardID, "Villainy", $currentPlayer)) {
            $costModifier -= 2;
            $remove = true;
          }
          break;
        case "4919000710"://Home One
          $costModifier -= 3;
          $remove = true;
          break;
        case "5351496853"://Gideon's Light Cruiser
          $costModifier -= 99;
          $remove = true;
          break;
        case "2756312994"://Alliance Dispatcher
          $costModifier -= 1;
          $remove = true;
          break;
        case "3509161777"://You're My Only Hope
          $costModifier -= PlayerRemainingHealth($player) <= 5 ? 99 : 5;
          $remove = true;
          break;
        case "5494760041"://Galactic Ambition
          $costModifier -= 99;
          $remove = true;
          break;
        case "3426168686"://Sneak Attack
          if($from != "PLAY") {
            $costModifier -= 3;
            $remove = true;
          }
          break;
        case "7870435409"://Bib Fortuna
          $costModifier -= 1;
          $remove = true;
          break;
        case "8506660490":
          $costModifier -= 99;
          break;
        case "8968669390"://U-Wing Reinforcement
          $costModifier -= 99;
          break;
        default: break;
      }
      if($remove) RemoveCurrentTurnEffect($i);
    }
  }
  return $costModifier;
}

function CurrentEffectPreventDamagePrevention($player, $type, $damage, $source)
{
  global $currentTurnEffects;
  for($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {

        default: break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
  return $damage;
}

function CurrentEffectDamagePrevention($player, $type, $damage, $source, $preventable, $uniqueID=-1)
{
  global $currentPlayer, $currentTurnEffects;
  for($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0 && $damage > 0; $i -= CurrentTurnEffectPieces()) {
    if($uniqueID != -1 && $currentTurnEffects[$i + 2] != $uniqueID) continue;
    $remove = false;
    if($currentTurnEffects[$i + 1] == $player || $uniqueID != -1) {
      $effects = explode("-", $currentTurnEffects[$i]);
      switch($effects[0]) {
        case "pv4n1n3gyg"://Cleric's Robe
          if($preventable) $damage -= 1;
          $remove = true;
          break;
        default: break;
      }
      if($remove) RemoveCurrentTurnEffect($i);
    }
  }
  return $damage;
}

function CurrentEffectAttackAbility()
{
  global $currentTurnEffects, $combatChain, $mainPlayer;
  global $CS_PlayIndex;
  if(count($combatChain) == 0) return;
  $attackID = $combatChain[0];
  $attackType = CardType($attackID);
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "Tx6iJQNSA6"://Majestic Spirit's Crest
          if(!IsAlly($attackID)) Draw($mainPlayer);
          break;
        default:
          break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
}

function CurrentEffectPlayAbility($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $actionPoints, $CS_LastDynCost;

  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {

        default:
          break;
      }
      if($remove) RemoveCurrentTurnEffect($i);
    }
  }
  return false;
}

function CurrentEffectPlayOrActivateAbility($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer;

  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {

        default:
          break;
      }
      if($remove) RemoveCurrentTurnEffect($i);
    }
  }
  $currentTurnEffects = array_values($currentTurnEffects); //In case any were removed
  return false;
}

function CurrentEffectGrantsNonAttackActionGoAgain($cardID)
{
  global $currentTurnEffects, $currentPlayer;
  $hasGoAgain = false;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {

        default:
          break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
  return $hasGoAgain;
}

function CurrentEffectGrantsGoAgain()
{
  global $currentTurnEffects, $mainPlayer, $combatChainState, $CCS_AttackFused;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $mainPlayer && IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i)) {
      switch ($currentTurnEffects[$i]) {

        default:
          break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsGoAgain()
{
  global $currentTurnEffects, $mainPlayer;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch($currentTurnEffects[$i]) {
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsDefenseReaction($from)
{
  global $currentTurnEffects, $currentPlayer;
  $reactionPrevented = false;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {

        default:
          break;
      }
    }
  }
  return $reactionPrevented;
}

function CurrentEffectPreventsDraw($player, $isMainPhase)
{
  global $currentTurnEffects;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {
        default: break;
      }
    }
  }
  return false;
}

function CurrentEffectIntellectModifier()
{
  global $currentTurnEffects, $mainPlayer;
  $intellectModifier = 0;
  for($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
    if($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch($currentTurnEffects[$i]) {

        default: break;
      }
    }
  }
  return $intellectModifier;
}

function CurrentEffectEndTurnAbilities()
{
  global $currentTurnEffects, $mainPlayer;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    $params = explode("_", $currentTurnEffects[$i]);
    $cardID = $params[0];
    if(count($params) > 1) $subparam = $params[1];
    if(SearchCurrentTurnEffects($cardID . "-UNDER", $currentTurnEffects[$i + 1])) {
      AddNextTurnEffect($currentTurnEffects[$i], $currentTurnEffects[$i + 1]);
    }
    switch($cardID) {
      case "3426168686-2"://Sneak Attack
        $ally = new Ally("MYALLY-" . SearchAlliesForUniqueID($currentTurnEffects[$i+2], $currentTurnEffects[$i+1]), $currentTurnEffects[$i+1]);
        $ally->Destroy();
        break;
      case "1626462639"://Change of Heart
        $index = SearchAlliesForUniqueID($currentTurnEffects[$i+2], $currentTurnEffects[$i+1]);
        if($index > -1) {
          $ally = new Ally("MYALLY-" . $index, $currentTurnEffects[$i+1]);
          $owner = $ally->Owner();
          WriteLog("Change of Heart unit reverted control of " . CardLink($ally->CardID(), $ally->CardID()) . "back to player $owner");
          AddDecisionQueue("PASSPARAMETER", $owner, "THEIRALLY-" . $index, 1);
          AddDecisionQueue("MZOP", $owner, "TAKECONTROL", 1);
        }
        break;
      default: break;
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
}


function CurrentEffectStartRegroupAbilities()
{
  global $currentTurnEffects, $mainPlayer;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    $params = explode("_", $currentTurnEffects[$i]);
    $cardID = $params[0];
    if(count($params) > 1) $subparam = $params[1];
    if(SearchCurrentTurnEffects($cardID . "-UNDER", $currentTurnEffects[$i + 1])) {
      AddNextTurnEffect($currentTurnEffects[$i], $currentTurnEffects[$i + 1]);
    }
    switch($cardID) {
      case "2522489681"://Zorii Bliss
        PummelHit($currentTurnEffects[$i+1]);
        break;
      default: break;
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
}

function CurrentEffectStartTurnAbilities()
{
  global $currentTurnEffects, $mainPlayer;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    $cardID = substr($currentTurnEffects[$i], 0, 6);
    if(SearchCurrentTurnEffects($cardID . "-UNDER", $currentTurnEffects[$i + 1])) {
      AddNextTurnEffect($currentTurnEffects[$i], $currentTurnEffects[$i + 1]);
    }
    switch($currentTurnEffects[$i]) {
      case "5954056864": case "5e90bd91b0"://Han Solo
        MZChooseAndDestroy($currentTurnEffects[$i+1], "MYRESOURCES", context:"Choose a resource to destroy");
        break;
      case "8800836530"://No Good To Me Dead
        $ally = new Ally("MYALLY-" . SearchAlliesForUniqueID($currentTurnEffects[$i+2], $currentTurnEffects[$i+1]), $currentTurnEffects[$i+1]);
        $ally->Exhaust();
        $remove = true;
        break;
      default: break;
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
}

function IsCombatEffectActive($cardID)
{
  global $combatChain, $currentPlayer;
  if(count($combatChain) == 0) return;
  $attackID = $combatChain[0];
  $effectArr = explode("-", $cardID);
  $cardID = $effectArr[0];
  switch($cardID)
  {
    case "2587711125": return true;//Disarm
    case "2569134232": return true;//Jedha City
    case "1323728003": return true;//Electrostaff
    case "3809048641": return true;//Surprise Strike
    case "3038238423": return true;//Fleet Lieutenant
    case "8244682354": return true;//Jyn Erso
    case "8600121285": return true;//IG-88
    case "6954704048": return true;//Heroic Sacrifice
    case "20f21b4948": return true;//Jyn Erso
    case "9097690846": return true;//Snowtrooper Lieutenant
    case "9210902604": return true;//Precision Fire
    case "8297630396": return true;//Shoot First
    case "5464125379": return true;//Strafing Gunship
    case "8495694166": return true;//Jedi Lightsaber
    case "3789633661": return true;//Cunning
    case "8988732248": return true;//Rebel Assault
    case "6514927936": return true;//Leia Organa
    case "0802973415": return true;//Outflank
    case "1480894253": return true;//Kylo Ren
    case "2503039837": return true;//Moff Gideon Leader
    case "4721657243": return true;//Kihraxz Heavy Fighter
    case "7171636330": return true;//Chain Code Collector
    default: return false;
  }
}

function IsCombatEffectPersistent($cardID)
{
  global $currentPlayer;
  $effectArr = explode(",", $cardID);
  switch($cardID) {
    case "2587711125": return true;//Disarm
    case "2569134232": return true;//Jedha City
    case "3789633661": return true;//Cunning
    case "1480894253": return true;//Kylo Ren
    default:
      return false;
  }
}

function IsEffectPersistent($cardID)
{
  global $currentPlayer;
  $effectArr = explode(",", $cardID);
  switch($cardID) {
    case "7dedg616r0": return true;//Freydis, Master Tactician
    default:
      return false;
  }
}

function BeginEndPhaseEffects()
{
  global $currentTurnEffects, $mainPlayer, $EffectContext;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    $EffectContext = $currentTurnEffects[$i];
    if(IsEffectPersistent($EffectContext)) AddNextTurnEffect($EffectContext, $currentTurnEffects[$i+1]);
    switch($currentTurnEffects[$i]) {
      default:
        break;
    }
  }
}

function BeginEndPhaseEffectTriggers()
{
  global $currentTurnEffects, $mainPlayer;
  for($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    switch($currentTurnEffects[$i]) {
      case "blq7qXGvWH":
        DiscardHand($mainPlayer);
        WriteLog("Arcane Disposition discarded your hand");
        break;
      default: break;
    }
  }
}

function ActivateAbilityEffects()
{
  global $currentPlayer, $currentTurnEffects;
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch($currentTurnEffects[$i]) {

        default:
          break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
  $currentTurnEffects = array_values($currentTurnEffects);
}

function CurrentEffectNameModifier($effectID, $effectParameter)
{
  $name = "";
  switch($effectID)
  {

    default: break;
  }
  return $name;
}

function CurrentEffectAllyEntersPlay($player, $index)
{
  global $currentTurnEffects;
  $allies = &GetAllies($player);
  for($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = false;
    if($currentTurnEffects[$i + 1] == $player) {
      switch($currentTurnEffects[$i]) {
        case "RfPP8h16Wv":
          if(SubtypeContains($allies[$index], "BEAST", $player) || SubtypeContains($allies[$index], "ANIMAL", $player))
          {
            ++$allies[$index+2];
            ++$allies[$index+7];
            $remove = 1;
          }
          break;
        default:
          break;
      }
    }
    if($remove) RemoveCurrentTurnEffect($i);
  }
  $currentTurnEffects = array_values($currentTurnEffects);
}

?>
