<ul class="rgt-bar" id="rightside-menu">
    @if($marketspace->user_type=="Work")
      <li>
          <a href="{{ route('skills') }}" class="skills-btn">
              <span class="rgt-bar-img"><img src="{{ asset('images/skills-icon.png') }}" alt=""></span>
              <span class="rgt-bar-txt">Skills </span>
          </a>
      </li>
      <li>
          <a href="{{ route('work-experience') }}" class="work-btn">
              <span class="rgt-bar-img"><img src="{{ asset('images/work-icon.png') }}" alt=""></span>
              <span class="rgt-bar-txt">Work <br>Experience</span>
          </a>
      </li>
      <li>
          <a href="{{ route('education') }}" class="education-btn">
              <span class="rgt-bar-img"><img src="{{ asset('images/education-icon.png') }}" alt=""></span>
              <span class="rgt-bar-txt">Education <br> Qualification</span>
          </a>
      </li>
      <li>
          <a href="{{ route('training-attended') }}" class="training-btn">
              <span class="rgt-bar-img"><img src="{{ asset('images/education-icon.png') }}" alt=""></span>
              <span class="rgt-bar-txt">Training <br>Attended</span>
          </a>
      </li>
      <li>
          <a href="{{ route('reference') }}" class="reference-btn">
              <span class="rgt-bar-img"><img src="{{ asset('images/reference-icon.png') }}" alt=""></span>
              <span class="rgt-bar-txt">Reference</span>
          </a>
      </li>
      @else  
      <li>
        <a href="{{ route('marketspace-ib') }}" class="skills-btn">
            <span class="rgt-bar-img"><img src="{{ asset('images/skills-icon.png') }}" alt=""></span>
            <span class="rgt-bar-txt">IB </span>
        </a>
    </li>
    @endif
  </ul>